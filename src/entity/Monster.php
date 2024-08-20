<?php

namespace pocketmine\entity;

use pocketmine\entity\Living;
use pocketmine\player\Player;

abstract class Monster extends Living {

    protected function initEntity() : void {
        parent::initEntity();
        $this->setMaxHealth(20);
        $this->setMovementSpeed(1.0);
    }

    public function chasePlayer(Player $player) : void {
        $this->lookAt($player->getPosition());
        $direction = $player->getPosition()->subtract($this->getPosition())->normalize();
        $this->setMotion($direction->multiply($this->getMovementSpeed()));
    }

    public function attackPlayer(Player $player) : void {
        // Logika dasar serangan jarak dekat
        if ($this->distance($player) <= 1.5) {
            $player->setHealth($player->getHealth() - 2);
        }
    }

    public function onUpdate(int $currentTick) : bool {
        $nearestPlayer = $this->getLevel()->getNearestEntity($this, 10, Player::class);
        if ($nearestPlayer instanceof Player) {
            $this->chasePlayer($nearestPlayer);
            $this->attackPlayer($nearestPlayer);
        }
        return parent::onUpdate($currentTick);
    }
}
