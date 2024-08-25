<?php

namespace pocketmine\entity;

use pocketmine\player\Player;
use pocketmine\item\Item;
use pocketmine\world\format\Chunk;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;

abstract class Creature extends Living {
    public function __construct(Chunk $chunk, CompoundTag $nbt) {
        parent::__construct($chunk, $nbt);
    }

    // Armor stands, when implemented, should also check this.
    public function onInteract(Player $player, Item $item, Vector3 $clickedPos): bool {
        if ($item->getId() === Item::NAME_TAG && !$player->isAdventure()) {
            return $this->applyNameTag($player, $item);
        }
        return false;
    }

    // Structured like this so I can override nametags in player and dragon classes
    // without overriding onInteract.
    protected function applyNameTag(Player $player, Item $item): bool {
        if ($item->hasCustomName()) {
            $this->setNameTag($item->getCustomName());
            $this->setNameTagVisible(true);
            return true; // onInteract: true = decrease count
        }
        return false;
    }
}

