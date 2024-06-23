<?php

/**
 * Cow.php
 *
 * @license MIT
 * @link https://github.com/XPocketMP
 * 
 * XPocketMP
 */

namespace pocketmine\entity;

use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use pocketmine\entity\Living;
use function mt_rand;

class Cow extends Living {

    public const NETWORK_ID = self::COW;

    protected float $width = 0.9;
    protected float $height = 1.4;

    public function __construct(World $world, CompoundTag $nbt){
        parent::__construct($world, $nbt);
        $this->setMaxHealth(10);
        $this->setHealth(10);
    }

    public function getName() : string{
        return "Cow";
    }

    protected function initEntity(CompoundTag $nbt) : void{
        parent::initEntity($nbt);
    }

    public function getDrops() : array{
        return [
            ItemFactory::getInstance()->get(ItemIds::RAW_BEEF, 0, mt_rand(1, 3)),
            ItemFactory::getInstance()->get(ItemIds::LEATHER, 0, mt_rand(0, 2))
        ];
    }
}
