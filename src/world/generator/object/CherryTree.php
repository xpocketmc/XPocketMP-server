<?php

namespace pocketmine\world\generator\object;

use pocketmine\block\VanillaBlocks;
use pocketmine\world\ChunkManager;
use pocketmine\math\Vector3;

class CherryTree{

    public static function placeObject(ChunkManager $world, Vector3 $position): void{
        $trunkHeight = mt_rand(4, 7);

        $baseX = (int)$position->x;
        $baseY = (int)$position->y;
        $baseZ = (int)$position->z;

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
