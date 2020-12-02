<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\permission;

use Ds\Set;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginException;
use pocketmine\timings\Timings;
use function count;
use function spl_object_id;

class PermissibleBase implements Permissible{
	/**
	 * @var bool[]
	 * @phpstan-var array<string, bool>
	 */
	private $rootPermissions = [
		DefaultPermissions::ROOT_USER => true
	];

	/** @var PermissionAttachment[] */
	private $attachments = [];

	/** @var PermissionAttachmentInfo[] */
	private $permissions = [];

	/**
	 * @var Set|\Closure[]
	 * @phpstan-var Set<\Closure(array<string, bool> $changedPermissionsOldValues) : void>
	 */
	private $permissionRecalculationCallbacks;

	public function __construct(bool $isOp){
		$this->permissionRecalculationCallbacks = new Set();

		//TODO: we can't setBasePermission here directly due to bad architecture that causes recalculatePermissions to explode
		//so, this hack has to be done here to prevent permission recalculations until it's fixed...
		if($isOp){
			$this->rootPermissions[DefaultPermissions::ROOT_OPERATOR] = true;
		}
		//TODO: permissions need to be recalculated here, or inherited permissions won't work
	}

	public function setBasePermission($name, bool $grant) : void{
		if($name instanceof Permission){
			$name = $name->getName();
		}
		$this->rootPermissions[$name] = $grant;
		$this->recalculatePermissions();
	}

	public function unsetBasePermission($name) : void{
		unset($this->rootPermissions[$name instanceof Permission ? $name->getName() : $name]);
		$this->recalculatePermissions();
	}

	/**
	 * @param Permission|string $name
	 */
	public function isPermissionSet($name) : bool{
		return isset($this->permissions[$name instanceof Permission ? $name->getName() : $name]);
	}

	/**
	 * @param Permission|string $name
	 */
	public function hasPermission($name) : bool{
		if($name instanceof Permission){
			$name = $name->getName();
		}

		if($this->isPermissionSet($name)){
			return $this->permissions[$name]->getValue();
		}

		return false;
	}

	/**
	 * //TODO: tick scheduled attachments
	 */
	public function addAttachment(Plugin $plugin, ?string $name = null, ?bool $value = null) : PermissionAttachment{
		if(!$plugin->isEnabled()){
			throw new PluginException("Plugin " . $plugin->getDescription()->getName() . " is disabled");
		}

		$result = new PermissionAttachment($plugin, $this);
		$this->attachments[spl_object_id($result)] = $result;
		if($name !== null and $value !== null){
			$result->setPermission($name, $value);
		}

		$this->recalculatePermissions();

		return $result;
	}

	public function removeAttachment(PermissionAttachment $attachment) : void{
		if(isset($this->attachments[spl_object_id($attachment)])){
			unset($this->attachments[spl_object_id($attachment)]);
			if(($ex = $attachment->getRemovalCallback()) !== null){
				$ex->attachmentRemoved($attachment);
			}

			$this->recalculatePermissions();

		}

	}

	public function recalculatePermissions() : void{
		Timings::$permissibleCalculationTimer->startTiming();

		$permManager = PermissionManager::getInstance();
		$permManager->unsubscribeFromAllPermissions($this);
		$oldPermissions = $this->permissions;
		$this->permissions = [];

		foreach($this->rootPermissions as $name => $isGranted){
			$perm = $permManager->getPermission($name);
			if($perm === null){
				throw new \InvalidStateException("Unregistered root permission $name");
			}
			$this->permissions[$name] = new PermissionAttachmentInfo($name, null, $isGranted);
			$permManager->subscribeToPermission($name, $this);
			$this->calculateChildPermissions($perm->getChildren(), false, null);
		}

		foreach($this->attachments as $attachment){
			$this->calculateChildPermissions($attachment->getPermissions(), false, $attachment);
		}

		$diff = [];
		Timings::$permissibleCalculationDiffTimer->time(function() use ($oldPermissions, &$diff) : void{
			foreach($this->permissions as $permissionAttachmentInfo){
				$name = $permissionAttachmentInfo->getPermission();
				if(!isset($oldPermissions[$name])){
					$diff[$name] = false;
				}elseif($oldPermissions[$name]->getValue() !== $permissionAttachmentInfo->getValue()){
					//permission was previously unset OR the value of the permission changed
					//we don't care who assigned the permission, only that the result is different
					$diff[$name] = $oldPermissions[$name]->getValue();
				}
				unset($oldPermissions[$name]);
			}
			//oldPermissions now only contains permissions that are no longer set after recalculation
			foreach($oldPermissions as $permissionAttachmentInfo){
				$diff[$permissionAttachmentInfo->getPermission()] = $permissionAttachmentInfo->getValue();
			}
		});

		Timings::$permissibleCalculationCallbackTimer->time(function() use ($diff) : void{
			if(count($diff) > 0){
				foreach($this->permissionRecalculationCallbacks as $closure){
					$closure($diff);
				}
			}
		});

		Timings::$permissibleCalculationTimer->stopTiming();
	}

	/**
	 * @param bool[]                    $children
	 */
	private function calculateChildPermissions(array $children, bool $invert, ?PermissionAttachment $attachment) : void{
		$permManager = PermissionManager::getInstance();
		foreach($children as $name => $v){
			$perm = $permManager->getPermission($name);
			$value = ($v xor $invert);
			$this->permissions[$name] = new PermissionAttachmentInfo($name, $attachment, $value);
			$permManager->subscribeToPermission($name, $this);

			if($perm instanceof Permission){
				$this->calculateChildPermissions($perm->getChildren(), !$value, $attachment);
			}
		}
	}

	/**
	 * @return \Closure[]|Set
	 * @phpstan-return Set<\Closure(array<string, bool> $changedPermissionsOldValues) : void>
	 */
	public function getPermissionRecalculationCallbacks() : Set{ return $this->permissionRecalculationCallbacks; }

	/**
	 * @return PermissionAttachmentInfo[]
	 */
	public function getEffectivePermissions() : array{
		return $this->permissions;
	}

	public function destroyCycles() : void{
		PermissionManager::getInstance()->unsubscribeFromAllPermissions($this);
		$this->permissions = []; //PermissionAttachmentInfo doesn't reference Permissible anymore, but it references PermissionAttachment which does
		$this->attachments = []; //this might still be a problem if the attachments are still referenced, but we can't do anything about that
		$this->permissionRecalculationCallbacks->clear();
	}
}
