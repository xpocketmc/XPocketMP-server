<?php

namespace pocketmine\world\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class CatMeowSound extends PlaySound {

    public function __construct(Vector3 $pos){
        parent::__construct("mob.cat.meow", $pos, 1.0, 1.0); // volume 1.0, pitch 1.0
    }

    public function encode(Player $player): array {
        $pk = new PlaySoundPacket();
        $pk->soundName = "mob.cat.meow";
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->volume = $this->volume;
        $pk->pitch = $this->pitch;
        return [$pk];
    }
}
