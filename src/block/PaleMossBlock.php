<?php

namespace pocketmine\block;

use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockToolType;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;

class PaleMossBlock extends Block{
    public function __construct(){
        parent::__construct(0, new BlockBreakInfo(0.2, BlockToolType::SHOVEL));
    }

    public function getName(): string{
        return "Pale Moss Block";
    }
}
