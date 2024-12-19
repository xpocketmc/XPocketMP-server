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

/**
 * Permission related classes
 */

namespace pocketmine\permission;

use pocketmine\lang\Translatable;

/**
 * Represents a permission
 */
class Permission{
	private Translatable|string $description;

	/**
	 * Creates a new Permission object to be attached to Permissible objects
	 *
	 * @param bool[] $children
	 * @phpstan-param array<string, bool> $children
	 */
	public function __construct(
		private string $name,
		Translatable|string|null $description = null,
		private array $children = []
	){
		$this->description = $description ?? ""; //TODO: wtf ????

		$this->recalculatePermissibles();
	}

	public function getName() : string{
		return $this->name;
	}

	/**
	 * @return bool[]
	 * @phpstan-return array<string, bool>
	 */
	public function getChildren() : array{
		return $this->children;
	}

	public function getDescription() : Translatable|string{
		return $this->description;
	}

	public function setDescription(Translatable|string $value) : void{
		$this->description = $value;
	}

	/**
	 * @return PermissibleInternal[]
	 */
	public function getPermissibles() : array{
		return PermissionManager::getInstance()->getPermissionSubscriptions($this->name);
	}

	public function recalculatePermissibles() : void{
		$perms = $this->getPermissibles();

		foreach($perms as $p){
			$p->recalculatePermissions();
		}
	}

	public function addChild(string $name, bool $value) : void{
		$this->children[$name] = $value;
		$this->recalculatePermissibles();
	}

	public function removeChild(string $name) : void{
		unset($this->children[$name]);
		$this->recalculatePermissibles();

	}
}
