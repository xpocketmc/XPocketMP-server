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

namespace pocketmine\block;

use pocketmine\block\utils\BlockEventHelper;
use pocketmine\block\utils\DirtType;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use function mt_rand;

class Mycelium extends Opaque{

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			VanillaBlocks::DIRT()->asItem()
		];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}

	public function ticksRandomly() : bool{
		return true;
	}

	public function onRandomTick() : void{
		//TODO: light levels
		$x = mt_rand($this->position->x - 1, $this->position->x + 1);
		$y = mt_rand($this->position->y - 2, $this->position->y + 2);
		$z = mt_rand($this->position->z - 1, $this->position->z + 1);
		$world = $this->position->getWorld();
		$block = $world->getBlockAt($x, $y, $z);
		if($block instanceof Dirt && $block->getDirtType() === DirtType::NORMAL){
			if($block->getSide(Facing::UP) instanceof Transparent){
				BlockEventHelper::spread($block, VanillaBlocks::MYCELIUM(), $this);
			}
		}
	}
}
