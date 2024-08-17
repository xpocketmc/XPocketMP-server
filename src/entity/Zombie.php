<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\entity\attributes\Attribute;
use pocketmine\entity\attributes\AttributeInfo;
use pocketmine\entity\attributes\AttributeManager;
use function mt_rand;

class Zombie extends Living {

    public static function getNetworkTypeId() : string { 
        return EntityIds::ZOMBIE; 
    }

    protected function getInitialSizeInfo() : EntitySizeInfo {
        return new EntitySizeInfo(1.8, 0.6); // Tinggi dan lebar entitas
    }

    public function getName() : string {
        return "Zombie";
    }

    public function getDrops() : array {
        $drops = [
            VanillaItems::ROTTEN_FLESH()->setCount(mt_rand(0, 2))
        ];

        if(mt_rand(0, 199) < 5){
            switch(mt_rand(0, 2)){
                case 0:
                    $drops[] = VanillaItems::IRON_INGOT();
                    break;
                case 1:
                    $drops[] = VanillaItems::CARROT();
                    break;
                case 2:
                    $drops[] = VanillaItems::POTATO();
                    break;
            }
        }

        return $drops;
    }

    public function getXpDropAmount() : int {
        return 5;
    }

    protected function onUpdate(int $currentTick): bool {
        if($this->isClosed()){
            return false;
        }

        // Cek apakah saat ini adalah malam hari
        if ($this->level->getTime() % Level::TIME_FULL >= Level::TIME_NIGHT) {
            $this->kill();
            return true;
        }

        // Cek apakah entitas terkena matahari
        if ($this->level->getBlockSkyLightAt($this->x, $this->y, $this->z) >= 15) {
            $this->setOnFire(100); // Terbakar selama 5 detik
        }

        $this->move();
        $this->updateMovement();

        return parent::onUpdate($currentTick);
    }

    private function move(): void {
        // Gerakan acak
        $motionX = (mt_rand(-1, 1) / 10.0); 
        $motionZ = (mt_rand(-1, 1) / 10.0); 

        // Mengatur kecepatan pergerakan
        $this->motion->x = $motionX;
        $this->motion->z = $motionZ;

        // Menggerakkan entitas
        $this->setMotion($this->motion);
        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
    }

    private function updateMovement(): void {
        // Arahkan entitas ke arah gerakan
        $this->lookAt($this->x + $this->motion->x, $this->y, $this->z + $this->motion->z);
        
        // Update animasi berjalan
        $this->setMetadataFlag(EntityMetadataFlags::IS_WALKING, true);
    }

    public function attack(EntityDamageEvent $source): void {
        if($source instanceof EntityDamageByEntityEvent) {
            $damager = $source->getDamager();
            if($damager instanceof Player) {
                // Menyerang pemain
                $damager->sendMessage("You have been attacked by a Zombie!");
            }
        }
        parent::attack($source);
    }

    public function spawnTo(Player $player): void {
        parent::spawnTo($player);
        // Efek suara saat spawn
        $this->playSound("mob.zombie.spawn", 1, 1); // Suara spawn contoh
    }

    private function playSound(string $sound, float $volume, float $pitch): void {
        $this->level->broadcastLevelSoundEvent($this, $sound, $volume, $pitch);
    }
    
    private function setMetadataFlag(int $flag, bool $value): void {
        $metadata = $this->getNetworkProperties();
        $metadata->setFlags($flag, $value);
        $this->updateMetadata($metadata);
    }
}
