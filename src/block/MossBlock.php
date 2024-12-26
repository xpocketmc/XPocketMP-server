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

use pocketmine\block\utils\ToolType;

class MossBlock extends Opaque{

	protected float $hardness = 0.1;
	protected float $blastResistance = 1.0;

	public function getToolType() : ToolType{
		return BlockToolType::SHOVEL();
	}

	public function onRandomTick() : void{
		$world = $this->position->getWorld();
		foreach($this->getSpreadableNeighbors() as $neighbor){
			$block = $world->getBlockAt($neighbor);
			if($block instanceof Dirt && !$block->isCovered()){
				$world->setBlockAt($neighbor, new MossBlock());
			}
		}
	}

	private function getSpreadableNeighbors() : array{
		$pos = $this->position;
		return[
			$pos->north(),
			$pos->south(),
			$pos->east(),
			$pos->west(),
			$pos->up(),
		];
	}
}
