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

namespace XPocketMP\world\generator\populator;

use XPocketMPlock\BlockTypeIds;
use XPocketMPlock\Leaves;
use XPocketMPlock\VanillaBlocks;
use XPocketMP\utils\Random;
use XPocketMP\world\ChunkManager;
use XPocketMP\world\format\Chunk;

class TallGrass implements Populator{
	private int $randomAmount = 1;
	private int $baseAmount = 0;

	public function setRandomAmount(int $amount) : void{
		$this->randomAmount = $amount;
	}

	public function setBaseAmount(int $amount) : void{
		$this->baseAmount = $amount;
	}

	public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random) : void{
		$amount = $random->nextRange(0, $this->randomAmount) + $this->baseAmount;

		$block = VanillaBlocks::TALL_GRASS();
		for($i = 0; $i < $amount; ++$i){
			$x = $random->nextRange($chunkX * Chunk::EDGE_LENGTH, $chunkX * Chunk::EDGE_LENGTH + (Chunk::EDGE_LENGTH - 1));
			$z = $random->nextRange($chunkZ * Chunk::EDGE_LENGTH, $chunkZ * Chunk::EDGE_LENGTH + (Chunk::EDGE_LENGTH - 1));
			$y = $this->getHighestWorkableBlock($world, $x, $z);

			if($y !== -1 && $this->canTallGrassStay($world, $x, $y, $z)){
				$world->setBlockAt($x, $y, $z, $block);
			}
		}
	}

	private function canTallGrassStay(ChunkManager $world, int $x, int $y, int $z) : bool{
		$b = $world->getBlockAt($x, $y, $z)->getTypeId();
		return ($b === BlockTypeIds::AIR || $b === BlockTypeIds::SNOW_LAYER) && $world->getBlockAt($x, $y - 1, $z)->getTypeId() === BlockTypeIds::GRASS;
	}

	private function getHighestWorkableBlock(ChunkManager $world, int $x, int $z) : int{
		for($y = 127; $y >= 0; --$y){
			$b = $world->getBlockAt($x, $y, $z);
			if($b->getTypeId() !== BlockTypeIds::AIR && !($b instanceof Leaves) && $b->getTypeId() !== BlockTypeIds::SNOW_LAYER){
				return $y + 1;
			}
		}

		return -1;
	}
}