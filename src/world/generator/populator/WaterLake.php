<?php

declare(strict_types=1);

namespace pocketmine\world\generator\populator;

use pocketmine\block\VanillaBlocks;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\object\Lake;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class WaterLake extends Populator{
    private int $amount = 2;

    public function setAmount(int $amount): void{
        $this->amount = $amount;
    }

    public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void{
        for($i = 0; $i < $this->amount; ++$i){
            if($random->nextBoundedInt(100) < 50){
                $x = ($chunkX << 4) + $random->nextBoundedInt(16);
                $z = ($chunkZ << 4) + $random->nextBoundedInt(16);
                $y = $random->nextBoundedInt(64);
                $lake = new Lake(VanillaBlocks::WATER());
                $lake->generate($world, $random, new Vector3($x, $y, $z));
            }
        }
    }
}
