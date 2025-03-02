<?php

/*
 *
 *  __  ______            _        _   __  __ ____
 *  \ \/ /  _ \ ___   ___| | _____| |_|  \/  |  _ \
 *   \  /| |_) / _ \ / __| |/ / _ \ __| |\/| | |_) |
 *   /  \|  __/ (_) | (__|   <  __/ |_| |  | |  __/
 *  /_/\_\_|   \___/ \___|_|\_\___|\__|_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License as published by
 * the Free Software Foundation
 * The files in XPocketMP are mostly from PocketMine-MP.
 * Developed by ClousClouds, PMMP Team
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\permission;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginException;
use pocketmine\utils\Utils;
use function spl_object_id;

class PermissionAttachment{
	/**
	 * @var bool[]
	 * @phpstan-var array<string, bool>
	 */
	private array $permissions = [];

	/**
	 * @var PermissibleInternal[]
	 * @phpstan-var array<int, PermissibleInternal>
	 */
	private array $subscribers = [];

	/**
	 * @throws PluginException
	 */
	public function __construct(
		private Plugin $plugin
	){
		if(!$plugin->isEnabled()){
			throw new PluginException("Plugin " . $plugin->getDescription()->getName() . " is disabled");
		}
	}

	public function getPlugin() : Plugin{
		return $this->plugin;
	}

	/**
	 * @return PermissibleInternal[]
	 * @phpstan-return array<int, PermissibleInternal>
	 */
	public function getSubscribers() : array{ return $this->subscribers; }

	/**
	 * @return bool[]
	 * @phpstan-return array<string, bool>
	 */
	public function getPermissions() : array{
		return $this->permissions;
	}

	private function recalculatePermissibles() : void{
		foreach($this->subscribers as $permissible){
			$permissible->recalculatePermissions();
		}
	}

	public function clearPermissions() : void{
		$this->permissions = [];
		$this->recalculatePermissibles();
	}

	/**
	 * @param bool[] $permissions
	 * @phpstan-param array<string, bool> $permissions
	 */
	public function setPermissions(array $permissions) : void{
		foreach(Utils::stringifyKeys($permissions) as $key => $value){
			$this->permissions[$key] = $value;
		}
		$this->recalculatePermissibles();
	}

	/**
	 * @param string[] $permissions
	 */
	public function unsetPermissions(array $permissions) : void{
		foreach($permissions as $node){
			unset($this->permissions[$node]);
		}
		$this->recalculatePermissibles();
	}

	public function setPermission(Permission|string $name, bool $value) : void{
		$name = $name instanceof Permission ? $name->getName() : $name;
		if(isset($this->permissions[$name])){
			if($this->permissions[$name] === $value){
				return;
			}
			/* Because of the way child permissions are calculated, permissions which were set later in time are
			 * preferred over earlier ones when conflicts in inherited permission values occur.
			 * Here's the kicker: This behaviour depends on PHP's internal array ordering, which maintains insertion
			 * order -- BUT -- assigning to an existing index replaces the old value WITHOUT changing the order.
			 * (what crazy person thought relying on this this was a good idea?!?!?!?!?!)
			 *
			 * This removes the old value so that the new value will be added at the end of the array's internal order
			 * instead of directly taking the place of the older value.
			 */
			unset($this->permissions[$name]);
		}
		$this->permissions[$name] = $value;
		$this->recalculatePermissibles();
	}

	public function unsetPermission(Permission|string $name) : void{
		$name = $name instanceof Permission ? $name->getName() : $name;
		if(isset($this->permissions[$name])){
			unset($this->permissions[$name]);
			$this->recalculatePermissibles();
		}
	}

	/**
	 * @internal
	 */
	public function subscribePermissible(PermissibleInternal $permissible) : void{
		$this->subscribers[spl_object_id($permissible)] = $permissible;
	}

	/**
	 * @internal
	 */
	public function unsubscribePermissible(PermissibleInternal $permissible) : void{
		unset($this->subscribers[spl_object_id($permissible)]);
	}
}
