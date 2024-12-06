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
 * @author XPocketMP Team
 * @link http://www.xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace XPocketMP\world\generator\object;

use XPocketMPlock\Block;
use XPocketMPlock\BlockTypeIds;
use XPocketMPlock\VanillaBlocks;
use XPocketMP\math\Vector3;
use XPocketMP\utils\Random;
use XPocketMP\world\ChunkManager;
use function count;

class TallGrass{

	public static function growGrass(ChunkManager $world, Vector3 $pos, Random $random, int $count = 15, int $radius = 10) : void{
		/** @var Block[] $arr */
		$arr = [
			VanillaBlocks::DANDELION(),
			VanillaBlocks::POPPY(),
			$tallGrass = VanillaBlocks::TALL_GRASS(),
			$tallGrass,
			$tallGrass,
			$tallGrass
		];
		$arrC = count($arr) - 1;
		for($c = 0; $c < $count; ++$c){
			$x = $random->nextRange($pos->x - $radius, $pos->x + $radius);
			$z = $random->nextRange($pos->z - $radius, $pos->z + $radius);
			if($world->getBlockAt($x, $pos->y + 1, $z)->getTypeId() === BlockTypeIds::AIR && $world->getBlockAt($x, $pos->y, $z)->getTypeId() === BlockTypeIds::GRASS){
				$world->setBlockAt($x, $pos->y + 1, $z, $arr[$random->nextRange(0, $arrC)]);
			}
		}
	}
}