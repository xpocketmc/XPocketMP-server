<?php

namespace pocketmine\world\biome;

use pocketmine\block\VanillaBlocks;
use pocketmine\world\generator\populator\TreePopulator;
use pocketmine\world\generator\object\CherryTree;

class CherryBiome extends Biome{
    public function __construct(){
        parent::__construct();

        $this->setGroundCover([
            VanillaBlocks::GRASS()->asBlockState(),
            VanillaBlocks::DIRT()->asBlockState(),
        ]);

        $this->temperature = 0.7;
        $this->rainfall = 0.8;

        $treePopulator = new TreePopulator();
        $treePopulator->setBaseAmount(2);
        $treePopulator->addTree(CherryTree::class, 10);
        $this->addPopulator($treePopulator);
    }

    public function getName(): string{
        return "Cherry Biome";
    }
}
