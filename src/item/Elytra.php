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

namespace pocketmine\item;

use pocketmine\inventory\ArmorInventory;

class Elytra extends Armor {
	public function __construct(ItemIdentifier $identifier, string $name) {
		parent::__construct($identifier, $name, new ArmorTypeInfo(0, 432, ArmorInventory::SLOT_CHEST));
	}

	public function getMaxDurability() : int {
		return 432;
	}

	public function applyDamage(int $amount) : bool {
		$this->setDamage($this->getDamage() + $amount);
		return $this->getDamage() >= $this->getMaxDurability();
	}
}
