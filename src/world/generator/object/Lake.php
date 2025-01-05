<?php

declare(strict_types=1);

namespace pocketmine\world\generator\object;

use pocketmine\block\Block;
use pocketmine\world\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Lake{
	private Block $block;

	public function __construct(Block $block){
		$this->block = $block;
	}

	public function generate(ChunkManager $world, Random $random, Vector3 $position) : void{
		$radiusX = $random->nextBoundedInt(4) + 4;
		$radiusY = $random->nextBoundedInt(2) + 2;
		$radiusZ = $random->nextBoundedInt(4) + 4;

		for($x = -$radiusX; $x <= $radiusX; ++$x){
			for($y = -$radiusY; $y <= $radiusY; ++$y){
				for($z = -$radiusZ; $z <= $radiusZ; ++$z){
					$distance = ($x ** 2) / $radiusX ** 2 + ($y ** 2) / $radiusY ** 2 + ($z ** 2) / $radiusZ ** 2;
					if($distance <= 1) {
						$world->setBlockAt((int)$position->x + $x, (int)$position->y + $y, (int)$position->z + $z, $this->block);
					}
				}			
			}
		}
	}
}
