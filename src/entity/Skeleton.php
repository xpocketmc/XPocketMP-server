<?php

namespace pocketmine\entity;

use pocketmine\entity\projectile\Arrow;
use pocketmine\entity\Location;
use pocketmine\player\Player;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\math\Vector3;
use pocketmine\world\World;

class Skeleton extends Monster {

    protected function initEntity(CompoundTag $nbt) : void {
        parent::initEntity($nbt);
        $this->setMaxHealth(20); // Skeleton memiliki nyawa 20
        $this->setMovementSpeed(1.2); // Skeleton sedikit lebih cepat
    }

    public function onUpdate(int $currentTick) : bool {
        if ($this->isDaytime() && !$this->isInShade() && !$this->isWearingHelmet()) {
            $this->setOnFire(100); // Membakar skeleton selama 5 detik
        }

        $nearestPlayer = $this->getWorld()->getNearestEntity($this->getPosition(), 20, Player::class);
        if ($nearestPlayer instanceof Player) {
            $this->chaseOrRetreat($nearestPlayer); // Skeleton mengejar atau menjaga jarak
            $this->attackPlayer($nearestPlayer); // Skeleton menyerang pemain
        }
        return parent::onUpdate($currentTick);
    }

    public function attackPlayer(Player $player) : void {
        $distance = sqrt($this->getPosition()->distanceSquared($player->getPosition()));

        if ($distance > 5 && $distance < 20) {
            $this->shootArrowAtPlayer($player);
        }
    }

    protected function chaseOrRetreat(Player $player) : void {
        $distance = sqrt($this->getPosition()->distanceSquared($player->getPosition()));

        if ($distance > 10) {
            $this->chasePlayer($player); 
        } elseif ($distance < 5) {
            $this->retreatFromPlayer($player);
        } else {
            $this->lookAt($player->getPosition());
        }
    }

    protected function shootArrowAtPlayer(Player $player) : void {
        $direction = $player->getPosition()->subtract($this->getPosition()->x, $this->getPosition()->y, $this->getPosition()->z)->normalize();
        $arrowLocation = new Location($this->getPosition()->getX(), $this->getPosition()->getY() + 1.5, $this->getPosition()->getZ(), $this->getWorld(), 0, 0);
        $arrow = new Arrow($arrowLocation, $this, true); // Buat arrow dengan lokasi dan entitas pemilik

        $arrow->setMotion($direction->multiply(2));
        $this->getWorld()->addEntity($arrow);
        $this->broadcastAnimation(new Animation($this, Animation::SWING_ARM)); 
        $this->playShootSound(); 
    }

    protected function retreatFromPlayer(Player $player) : void {
        $direction = $this->getPosition()->subtract($player->getPosition()->x, $player->getPosition()->y, $player->getPosition()->z)->normalize();
        $this->setMotion($direction->multiply($this->getMovementSpeed()));
    }

    protected function isInShade() : bool {
        $blockAbove = $this->getWorld()->getBlock($this->getPosition()->add(0, 1, 0));
        return !$blockAbove->getLightLevel() > 14;
    }

    protected function isDaytime() : bool {
        $time = $this->getWorld()->getTime() % World::TIME_FULL;
        return $time > World::TIME_DAY && $time < World::TIME_NIGHT;
    }

    protected function isWearingHelmet() : bool {
        $helmet = $this->getArmorInventory()->getHelmet();
        return !$helmet->isNull();
    }

    protected function playShootSound() : void {
        $this->getWorld()->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_BOW);
    }

    // Implementasi method abstract yang diperlukan
    public static function getInitialSizeInfo() : EntitySizeInfo {
        return new EntitySizeInfo(1.99, 0.6); // Ukuran skeleton (tinggi 1.99, lebar 0.6)
    }

    public function getName() : string {
        return "Skeleton";
    }

    public function getNetworkTypeId() : string {
        return EntityIds::SKELETON;
    }
}
