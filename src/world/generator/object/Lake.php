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

namespace pocketmine\world\generator\object;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;

class Lake{
	private Block $type;

	public function __construct(Block $type){
		$this->type = $type;
	}

	public function generate(ChunkManager $world, Random $random, Vector3 $position) : bool{
		$lakeSize = $random->nextBoundedInt(4) + 4;
		$shape = [];
		for($x = 0; $x < $lakeSize; ++$x){
			for($z = 0; $z < $lakeSize; ++$z){
				$shape[$x][$z] = ($random->nextFloat() < 0.8);
			}
		}

		for($x = 0; $x < $lakeSize; ++$x){
			for($z = 0; $z < $lakeSize; ++$z){
				if(!$shape[$x][$z])continue;

				$depth = $random->nextBoundedInt(4) + 2;
				for($y = 0; $y < $depth; ++$y){
					$target = $position->add($x - $lakeSize / 2, -$y, $z - $lakeSize / 2);
					$world->setBlockAt($target->x, $target->y, $target->z, $this->type);
				}
			}
		}

		for($x = -1; $x <= $lakeSize; ++$x){
			for($z = -1; $z <= $lakeSize; ++$z){
				if($x === -1 || $z === -1 || $x === $lakeSize || $z === $lakeSize){
					$above = $position->add($x - $lakeSize / 2, 1, $z - $lakeSize / 2);
					if($world->getBlockAt($above->x, $above->y, $above->z)->isSolid()){
						$world->setBlockAt($above->x, $above->y, $above->z, VanillaBlocks::AIR());
					}
				}
			}
		}
		return true;
	}
}
