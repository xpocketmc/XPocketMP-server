<?php

namespace pocketmine\entity;

use pocketmine\entity\Living;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;

abstract class Monster extends Living {

    protected function initEntity(CompoundTag $nbt) : void {
        parent::initEntity($nbt); // Pass the NBT parameter
        $this->setMaxHealth(20); // Default nyawa untuk semua monster
        $this->setMovementSpeed(1.0); // Default kecepatan monster
    }

    // Fungsi untuk mengejar pemain
    public function chasePlayer(Player $player) : void {
        $this->lookAt($player->getPosition()); // Mengarahkan ke pemain
        $direction = new Vector3(
            $player->getPosition()->getX() - $this->getPosition()->getX(),
            $player->getPosition()->getY() - $this->getPosition()->getY(),
            $player->getPosition()->getZ() - $this->getPosition()->getZ()
        );
        $direction = $direction->normalize(); // Arah ke pemain
        $this->setMotion($direction->multiply($this->getMovementSpeed())); // Bergerak ke arah pemain
    }

    // Fungsi dasar untuk menyerang, bisa di-override oleh subclass
    public function attackPlayer(Player $player) : void {
        // Logika dasar serangan jarak dekat
        $distance = sqrt($this->getPosition()->distanceSquared($player->getPosition())); // Gunakan distanceSquared()
        if ($distance <= 1.5) {
            $player->setHealth($player->getHealth() - 2); // Kurangi nyawa pemain
        }
    }

    // Fungsi untuk update monster tiap tick
    public function onUpdate(int $currentTick) : bool {
        $nearestPlayer = $this->getWorld()->getNearestEntity($this->getPosition(), 10, Player::class);
        if ($nearestPlayer instanceof Player) {
            $this->chasePlayer($nearestPlayer); // Kejar pemain
            $this->attackPlayer($nearestPlayer); // Serang pemain jika jaraknya dekat
        }
        return parent::onUpdate($currentTick);
    }
}
