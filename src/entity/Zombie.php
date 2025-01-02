<?php

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\sound\ZombieGroanSound;
use pocketmine\world\sound\ZombieHurtSound;
use pocketmine\world\sound\ZombieDeathSound;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use function mt_rand;

class Zombie extends Living{

    public static function getNetworkTypeId() : string{
        return EntityIds::ZOMBIE;
    }

    protected function getInitialSizeInfo() : EntitySizeInfo{
        return new EntitySizeInfo(1.8, 0.6);
    }

    public function getName() : string{
        return "Zombie";
    }

    public function getDrops() : array{
        $drops = [
            VanillaItems::ROTTEN_FLESH()->setCount(mt_rand(0, 2))
        ];

        if (mt_rand(0, 199) < 5){
            switch (mt_rand(0, 2)){
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

    public function getXpDropAmount() : int{
        return 5;
    }

    public function attackEntity(Player $player) : void{
        $distance = $this->getPosition()->distance($player->getPosition());

        if ($distance <= 1.5){
            $player->attack(mt_rand(2, 4));
            $this->getWorld()->addSound($this->getPosition(), new ZombieGroanSound());
        }
    }

    public function onUpdate(int $currentTick) : bool{
        $nearestPlayer = $this->getNearestPlayer();

        if ($nearestPlayer !== null){
            $distance = $this->getPosition()->distance($nearestPlayer->getPosition());

            if ($distance < 10){
                $this->setTarget($nearestPlayer);
                $this->moveTowards($nearestPlayer->getPosition());
                $this->playWalkingAnimation();
            }
        }

        return parent::onUpdate($currentTick);
    }

    public function onHurt() : void{
        $this->getWorld()->addSound($this->getPosition(), new ZombieHurtSound());
    }

    public function onDeath() : void{
        $this->getWorld()->addSound($this->getPosition(), new ZombieDeathSound());
    }

    private function getNearestPlayer() : ?Player{
        $nearestPlayer = null;
        $nearestDistance = PHP_FLOAT_MAX;

        foreach ($this->getWorld()->getPlayers() as $player) {
            $distance = $this->getPosition()->distance($player->getPosition());
            if ($distance < $nearestDistance) {
                $nearestPlayer = $player;
                $nearestDistance = $distance;
            }
        }

        return $nearestPlayer;
    }

    private function moveTowards(Vector3 $target) : void{
        $direction = $target->subtract($this->getPosition())->normalize();
        $this->motion->set($direction->x * 0.1, $this->motion->y, $direction->z * 0.1);
    }

    private function playWalkingAnimation() : void{
        $packet = new AnimatePacket();
        $packet->entityRuntimeId = $this->getId();
        $packet->action = AnimatePacket::ACTION_SWING_ARM,
        $this->getWorld()->broadcastPacketToViewers($this->getPosition(), $packet);
    }
}
