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
 * Developed: ClousClouds, PMMP Team
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\permission;

use pocketmine\plugin\Plugin;
use pocketmine\utils\ObjectSet;

trait PermissibleDelegateTrait{

	/** @var Permissible */
	private $perm;

	public function setBasePermission(Permission|string $name, bool $grant) : void{
		$this->perm->setBasePermission($name, $grant);
	}

	public function unsetBasePermission(Permission|string $name) : void{
		$this->perm->unsetBasePermission($name);
	}

	public function isPermissionSet(Permission|string $name) : bool{
		return $this->perm->isPermissionSet($name);
	}

	public function hasPermission(Permission|string $name) : bool{
		return $this->perm->hasPermission($name);
	}

	public function addAttachment(Plugin $plugin, ?string $name = null, ?bool $value = null) : PermissionAttachment{
		return $this->perm->addAttachment($plugin, $name, $value);
	}

	public function removeAttachment(PermissionAttachment $attachment) : void{
		$this->perm->removeAttachment($attachment);
	}

	public function recalculatePermissions() : array{
		return $this->perm->recalculatePermissions();
	}

	/**
	 * @return ObjectSet|\Closure[]
	 * @phpstan-return ObjectSet<\Closure(array<string, bool> $changedPermissionsOldValues) : void>
	 */
	public function getPermissionRecalculationCallbacks() : ObjectSet{
		return $this->perm->getPermissionRecalculationCallbacks();
	}

	/**
	 * @return PermissionAttachmentInfo[]
	 */
	public function getEffectivePermissions() : array{
		return $this->perm->getEffectivePermissions();
	}

}
