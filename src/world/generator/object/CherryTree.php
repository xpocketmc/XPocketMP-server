<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\world\generator\object;

use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector3;
use pocketmine\world\ChunkManager;
use function abs;
use function mt_rand;

class CherryTree{

	public static function placeObject(ChunkManager $world, Vector3 $position) : void{
		$trunkHeight = mt_rand(4, 7);

		$baseX = (int) $position->x;
		$baseY = (int) $position->y;
		$baseZ = (int) $position->z;

		for($y = 0; $y < $trunkHeight; $y++){
			$world->setBlockAt($baseX, $baseY + $y, $baseZ, VanillaBlocks::CHERRY_LOG());
		}

		$leafRadius = 2;
		for($dx = -$leafRadius; $dx <= $leafRadius; $dx++){
			for($dy = -1; $dy <= 1; $dy++){
				for($dz = -$leafRadius; $dz <= $leafRadius; $dz++){
					if(abs($dx) + abs($dy) + abs($dz) <= $leafRadius + 1){
						$world->setBlockAt(
							$baseX + $dx,
							$baseY + $trunkHeight + $dy,
							$baseZ + $dz,
							VanillaBlocks::CHERRY_LEAVES()
						);
					}
				}
			}
		}
	}
}
