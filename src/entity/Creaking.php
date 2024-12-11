<?php

namespace pocketmine\entity;

use pocketmine\entity\Monster;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\player\Player;
use pocketmine\world\sound\AnvilFallSound;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class Creaking extends Living{
    public const NETWORK_ID = self::CUSTOM_ENTITY;

    protected function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo(2.0, 1.0);
    }

    public function getName(): string {
        return "Creaking";
    }

    public function attack(Player $player): void{
        $player->setHealth($player->getHealth() - 8);
        $this->playCreakSound($player->getPosition());
    }

    public function onUpdate(int $currentTick): bool{
        if($this->getTarget() instanceof Player) {
            $this->moveTowards($this->getTarget()->getPosition());
        }
        return parent::onUpdate($currentTick);
    }

    private function playCreakSound(Vector3 $position): void{
        $packet = new PlaySoundPacket();
        $packet->soundName = "random.anvil_land";
        $packet->x = $position->x;
        $packet->y = $position->y;
        $packet->z = $position->z;
        $packet->volume = 1;
        $packet->pitch = 0.8;
        $this->getServer()->broadcastPacket($this->getViewers(), $packet);
    }
}
