<?php

namespace pocketmine;

use pocketmine\player\Player;
use pocketmine\utils\UUID;

class PocketPlayerUUID {

    protected Player $pyer;

    public function __construct(Player $pyer) {
        $this->pyer = $pyer;
    }

    public function setPlayer(Player $pyer): void {
        $this->pyer = $pyer;
    }

    public function getPlayerUUID(): string {
        return $this->pyer->getUniqueId()->toString();
    }

    public function getPlayerName(): string {
        return $this->pyer->getName();
    }

    public function isPlayerOnline(): bool {
        return $this->pyer->isOnline();
    }
}
