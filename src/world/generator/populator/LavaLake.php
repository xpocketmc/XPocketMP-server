<?php

declare(strict_types=1);

namespace pocketmine\world\generator\populator;

use pocketmine\block\VanillaBlocks;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\object\Lake;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class LavaLake extends Populator{
    private int $amount = 1;

    public function setAmount(int $amount): void{
        $this->amount = $amount;
    }

    public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void{
        for($i = 0; $i < $this->amount; ++$i){
            if($random->nextBoundedInt(100) < 25){
                $x = ($chunkX << 4) + $random->nextBoundedInt(16);
                $z = ($chunkZ << 4) + $random->nextBoundedInt(16);
                $y = $random->nextBoundedInt(32);
                $lake = new Lake(VanillaBlocks::LAVA());
                $lake->generate($world, $random, new Vector3($x, $y, $z));
            }
        }
    }
}
