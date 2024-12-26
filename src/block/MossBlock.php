<?php

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\ToolType;
use pocketmine\item\Item;
use pocketmine\item\ItemTypeIds;
use pocketmine\world\World;

class MossBlock extends Opaque{

    protected float $hardness = 0.1;
    protected float $blastResistance = 1.0;

    public function getToolType(): ToolType{
        return BlockToolType::SHOVEL();
    }

    public function onRandomTick(): void{
        $world = $this->position->getWorld();
        foreach($this->getSpreadableNeighbors() as $neighbor){
            $block = $world->getBlockAt($neighbor);
            if($block instanceof Dirt && !$block->isCovered()){
                $world->setBlockAt($neighbor, new MossBlock());
            }
        }
    }

    private function getSpreadableNeighbors(): array{
        $pos = $this->position;
        return[
            $pos->north(),
            $pos->south(),
            $pos->east(),
            $pos->west(),
            $pos->up(),
        ];
    }
}
