<?php

namespace pocketmine\world\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelEvent;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class CatMeowSound extends Sound {

    public function __construct(Vector3 $pos){
        parent::__construct("mob.cat.meow", $pos, 1.0, 1.0); // volume 1.0, pitch 1.0
	}
}
