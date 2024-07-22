<?php

declare(strict_types=1);

namespace pocketmine\entity\animation;

use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\AnimateEntityPacket;
use pocketmine\network\mcpe\protocol\types\AnimationType;

class SalmonSwimAnimation implements Animation {

    private Entity $entity;

    public function __construct(Entity $entity){
        $this->entity = $entity;
    }

    public function encode() : array{
        $packet = new AnimateEntityPacket();
        $packet->entityRuntimeId = $this->entity->getId();
        $packet->animation = AnimationType::SWIM;

        return [$packet];
    }
}
