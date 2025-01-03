<?php

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\block\VanillaBlocks;
use pocketmine\entity\animation\ArmSwingAnimation;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
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

    public function getXpDropAmount() : int{
        return 5;
    }

    public function attackEntity(Player $player) : void{
        $distance = $this->getPosition()->distance($player->getPosition());

        if($distance <= 1.5){
            $event = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, mt_rand(2, 4));
            $player->attack($event);
        }
    }

    public function onUpdate(int $currentTick) : bool{
        $nearestPlayer = $this->getNearestPlayer();

        if($nearestPlayer !== null){
            $distance = $this->getPosition()->distance($nearestPlayer->getPosition());

            if($distance < 10){
                $this->moveTowards($nearestPlayer->getPosition());
                $this->playWalkingAnimation();
            }
        }

        $block = $this->getWorld()->getBlock($this->getPosition());
        $positionAbove = $this->getPosition()->add(0, 1, 0);
        $blockAbove = $this->getWorld()->getBlock($positionAbove);

        if($this->isDaytime() && $block->getTypeId() !== VanillaBlocks::WATER()->getTypeId() && $blockAbove->isTransparent()){
            $this->setOnFire(20);
        }

        return parent::onUpdate($currentTick);
    }

    private function getNearestPlayer() : ?Player {
        $nearestPlayer = null;
        $nearestDistance = PHP_FLOAT_MAX;

        foreach($this->getWorld()->getPlayers() as $player) {
            $distance = $this->getPosition()->distance($player->getPosition());
            if($distance < $nearestDistance) {
                $nearestPlayer = $player;
                $nearestDistance = $distance;
            }
        }

        return $nearestPlayer;
    }

    private function moveTowards(Vector3 $target) : void {
        $direction = new Vector3(
            $target->x - $this->getPosition()->x,
            $target->y - $this->getPosition()->y,
            $target->z - $this->getPosition()->z
        );
        $direction = $direction->normalize();
        $this->motion->x = $direction->x * 0.1;
        $this->motion->y = $this->motion->y;
        $this->motion->z = $direction->z * 0.1;
    }

    private function playWalkingAnimation() : void {
        $animation = new ArmSwingAnimation($this);
        $this->broadcastAnimation($animation);
    }

    private function isDaytime() : bool {
        return $this->getWorld()->getTime() % 24000 < 12000;
    }
}
