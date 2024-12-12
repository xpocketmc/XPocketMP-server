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
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\MushroomBlockType;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use function mt_rand;

class RedMushroomBlock extends Opaque{
	protected MushroomBlockType $mushroomBlockType = MushroomBlockType::ALL_CAP;

	public function describeBlockItemState(RuntimeDataDescriber $w) : void{
		//these blocks always drop as all-cap, but may exist in other forms in the inventory (particularly creative),
		//so this information needs to be kept in the type info
		$w->enum($this->mushroomBlockType);
	}

	public function getMushroomBlockType() : MushroomBlockType{ return $this->mushroomBlockType; }

	/** @return $this */
	public function setMushroomBlockType(MushroomBlockType $mushroomBlockType) : self{
		$this->mushroomBlockType = $mushroomBlockType;
		return $this;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			VanillaBlocks::RED_MUSHROOM()->asItem()->setCount(mt_rand(0, 2))
		];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}

	public function getSilkTouchDrops(Item $item) : array{
		return [(clone $this)->setMushroomBlockType(MushroomBlockType::ALL_CAP)->asItem()];
	}

	public function getPickedItem(bool $addUserData = false) : Item{
		return (clone $this)->setMushroomBlockType(MushroomBlockType::ALL_CAP)->asItem();
	}
}
