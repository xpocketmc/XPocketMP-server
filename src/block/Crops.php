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

namespace pocketmine\block;

use pocketmine\block\utils\AgeableTrait;
use pocketmine\block\utils\BlockEventHelper;
use pocketmine\block\utils\CropGrowthHelper;
use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use function mt_rand;

abstract class Crops extends Flowable{
	use AgeableTrait;
	use StaticSupportTrait;

	public const MAX_AGE = 7;

	private function canBeSupportedAt(Block $block) : bool{
		return $block->getSide(Facing::DOWN)->getTypeId() === BlockTypeIds::FARMLAND;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($this->age < self::MAX_AGE && $item instanceof Fertilizer){
			$block = clone $this;
			$tempAge = $block->age + mt_rand(2, 5);
			if($tempAge > self::MAX_AGE){
				$tempAge = self::MAX_AGE;
			}
			$block->age = $tempAge;
			if(BlockEventHelper::grow($this, $block, $player)){
				$item->pop();
			}

			return true;
		}

		return false;
	}

	public function ticksRandomly() : bool{
		return $this->age < self::MAX_AGE;
	}

	public function onRandomTick() : void{
		if($this->age < self::MAX_AGE && CropGrowthHelper::canGrow($this)){
			$block = clone $this;
			++$block->age;
			BlockEventHelper::grow($this, $block, null);
		}
	}
}
