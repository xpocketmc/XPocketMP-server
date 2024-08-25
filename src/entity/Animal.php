<?php

namespace pocketmine\entity;

use pocketmine\entity\Entity;
use pocketmine\entity\EntityAgeable;
use pocketmine\nukkit\entity\EntityCreature;
use pocketmine\item\VanillaItems;
use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\CompoundTag;

abstract class Animal extends Creature implements Ageable {
    public function __construct(FullChunk $chunk, CompoundTag $nbt) {
        parent::__construct($chunk, $nbt);
    }

    public function isBaby(): bool {
        return $this->getDataFlag(self::DATA_FLAGS, Entity::DATA_FLAG_BABY);
    }

    public function isBreedingItem(VanillaItems $item): bool {
        return $item->getId() == VanillaItems::WHEAT; // default
    }
}

