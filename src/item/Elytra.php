<?php

declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\inventory\ArmorInventory;

class Elytra extends Armor {
    public function __construct(ItemIdentifier $identifier, string $name) {
        parent::__construct($identifier, $name, new ArmorTypeInfo(0, 432, ArmorInventory::SLOT_CHEST));
    }

    public function getMaxDurability(): int {
        return 432;
    }

    public function applyDamage(int $amount): bool {
        $this->setDamage($this->getDamage() + $amount);
        return $this->getDamage() >= $this->getMaxDurability();
    }
}
