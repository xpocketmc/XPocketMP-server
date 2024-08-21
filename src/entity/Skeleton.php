<?php

namespace pocketmine\entity;

use pocketmine\entity\projectile\Arrow;
use pocketmine\player\Player;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\math\Vector3;

class Skeleton extends Monster {

    protected function initEntity(CompoundTag $nbt) : void {
        parent::initEntity($nbt);
        $this->setMaxHealth(20); // Default health
        $this->setMovementSpeed(1.0); // Default speed
    }

    public function attackPlayer(Player $player) : void {
        $distance = sqrt($this->getPosition()->distanceSquared($player->getPosition()));

        if ($distance > 1.5 && $distance < 20) {
            $this->shootArrowAtPlayer($player);
        } else {
            parent::attackPlayer($player);
        }
    }

    protected function shootArrowAtPlayer(Player $player) : void {
        $direction = $player->getPosition()->subtract($this->getPosition())->normalize(); // Hitung arah panah
        $arrow = new Arrow($this->getWorld(), $this->getPosition()->add(0, 1.5, 0), $this); // Spawn panah sedikit di atas skeleton
        $arrow->setMotion($direction->multiply(2)); // Set kecepatan panah

        $this->getWorld()->addEntity($arrow);
    }

    public function onUpdate(int $currentTick) : bool {
        $nearestPlayer = $this->getWorld()->getNearestEntity($this->getPosition(), 20, Player::class);
        if ($nearestPlayer instanceof Player) {
            $this->chasePlayer($nearestPlayer); // Skeleton mengejar pemain
            $this->attackPlayer($nearestPlayer); // Skeleton menyerang pemain
        }
        return parent::onUpdate($currentTick);
    }
}
