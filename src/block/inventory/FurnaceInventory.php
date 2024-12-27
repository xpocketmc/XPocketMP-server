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

namespace pocketmine\block\inventory;

use pocketmine\crafting\FurnaceType;
use pocketmine\inventory\SimpleInventory;
use pocketmine\item\Item;
use pocketmine\world\Position;

class FurnaceInventory extends SimpleInventory implements BlockInventory{
	use BlockInventoryTrait;

	public const SLOT_INPUT = 0;
	public const SLOT_FUEL = 1;
	public const SLOT_RESULT = 2;

	public function __construct(
		Position $holder,
		private FurnaceType $furnaceType
	){
		$this->holder = $holder;
		parent::__construct(3);
	}

	public function getFurnaceType() : FurnaceType{ return $this->furnaceType; }

	public function getResult() : Item{
		return $this->getItem(self::SLOT_RESULT);
	}

	public function getFuel() : Item{
		return $this->getItem(self::SLOT_FUEL);
	}

	public function getSmelting() : Item{
		return $this->getItem(self::SLOT_INPUT);
	}

	public function setResult(Item $item) : void{
		$this->setItem(self::SLOT_RESULT, $item);
	}

	public function setFuel(Item $item) : void{
		$this->setItem(self::SLOT_FUEL, $item);
	}

	public function setSmelting(Item $item) : void{
		$this->setItem(self::SLOT_INPUT, $item);
	}
}
