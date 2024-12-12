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
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\block\inventory;

use pocketmine\inventory\SimpleInventory;
use pocketmine\world\Position;

class BrewingStandInventory extends SimpleInventory implements BlockInventory{
	use BlockInventoryTrait;

	public const SLOT_INGREDIENT = 0;
	public const SLOT_BOTTLE_LEFT = 1;
	public const SLOT_BOTTLE_MIDDLE = 2;
	public const SLOT_BOTTLE_RIGHT = 3;
	public const SLOT_FUEL = 4;

	public function __construct(Position $holder, int $size = 5){
		$this->holder = $holder;
		parent::__construct($size);
	}
}
