<?php

/**
 * CowSpawnEgg.php
 *
 * @license MIT
 * @link https://github.com/XPocketMP
 * 
 * Teks XPocketMP
 */

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\entity\Cow;
use pocketmine\entity\EntityFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\World;

class CowSpawnEgg extends Item {

    public function __construct(int $meta = 0) {
        parent::__construct(ItemIds::COW_SPAWN_EGG, $meta, "Cow Spawn Egg");
    }

    public function onClickAir(Player $player, Vector3 $directionVector) : bool {
        $location = $player->getLocation()->add(0, 1.5, 0);
        $nbt = EntityFactory::createBaseNBT($location);
        $entity = EntityFactory::getInstance()->create(Cow::class, $location, $nbt);
        if ($entity !== null) {
            $entity->spawnToAll();
            return true;
        }
        return false;
    }

    public function onClickBlock(Player $player, Block $block, Vector3 $clickedFace, Vector3 $clickVector) : bool {
        $location = $block->getSide($clickedFace)->getLocation()->add(0.5, 0, 0.5);
        $nbt = EntityFactory::createBaseNBT($location);
        $entity = EntityFactory::getInstance()->create(Cow::class, $location, $nbt);
        if ($entity !== null) {
            $entity->spawnToAll();
            return true;
        }
        return false;
    }
}
