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

use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;

class GrassPath extends Transparent{

	/**
	 * @return AxisAlignedBB[]
	 */
	protected function recalculateCollisionBoxes() : array{
		return [AxisAlignedBB::one()->trim(Facing::UP, 1 / 16)];
	}

	public function onNearbyBlockChange() : void{
		if($this->getSide(Facing::UP)->isSolid()){
			$this->position->getWorld()->setBlock($this->position, VanillaBlocks::DIRT());
		}
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			VanillaBlocks::DIRT()->asItem()
		];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}
}
