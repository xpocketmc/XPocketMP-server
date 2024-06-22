<?php

/*
__   _______           _        _   __  __  _____      __  __ _____  
 \ \ / /  __ \         | |      | | |  \/  |/ ____|    |  \/  |  __ \ 
  \ V /| |__) |__   ___| | _____| |_| \  / | |   ______| \  / | |__) |
   > < |  ___/ _ \ / __| |/ / _ \ __| |\/| | |  |______| |\/| |  ___/ 
  / . \| |  | (_) | (__|   <  __/ |_| |  | | |____     | |  | | |     
 /_/ \_\_|   \___/ \___|_|\_\___|\__|_|  |_|\_____|    |_|  |_|_|
 
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
*/

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\BlockToolType;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\level\Level;
use pocketmine\level\particle\HeartParticle;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\World;

class Sheep extends Animal {

    public const NETWORK_ID = EntityIds::SHEEP;

    public $width = 0.9;
    public $height = 1.3;

    private $inLove = false;
    private $mateTimer = 0;

    public function getName() : string {
        return "Sheep";
    }

    public function initEntity(CompoundTag $nbt) : void {
        parent::initEntity($nbt);

        if(isset($this->namedtag->Color)){
            $this->setColor($this->namedtag->getInt("Color"));
        }else{
            $this->setColor(mt_rand(0, 15));
        }
    }

    public function saveNBT() : CompoundTag {
        $tag = parent::saveNBT();
        $tag->setInt("Color", $this->getColor());
        return $tag;
    }

    public function isBaby() : bool {
        return $this->age < 100;
    }

    public function onInteract(Player $player, Vector3 $clickPos) : bool {
        $handItem = $player->getInventory()->getItemInHand();
        
        if($handItem->getId() === Item::SHEARS && !$this->isBaby()){
            $player->getInventory()->addItem(Item::get(Item::WOOL, $this->getColor(), 1));
            $this->getLevel()->addParticle(new HeartParticle($this));
            $this->setHealth($this->getMaxHealth());
            return true;
        }

        if ($handItem->getId() === Item::WHEAT) {
            $this->setInLove();
            return true;
        }

        return false;
    }

    public function getColor() : int {
        return $this->getDataPropertyManager()->getInt(Entity::DATA_COLOUR);
    }

    public function setColor(int $color) : void {
        $this->getDataPropertyManager()->setInt(Entity::DATA_COLOUR, $color);
    }

    public function attack(EntityDamageEvent $source) : void {
        parent::attack($source);
        if($source->isCancelled()){
            return;
        }
    }

    protected function updateMovement() : void {
        if($this->isInLove()) {
            $this->moveRandomly();
        } else {
            $this->avoidObstacles();
        }
    }

    private function moveRandomly() : void {
        $x = mt_rand(-10, 10);
        $z = mt_rand(-10, 10);
        $this->motion->x = $x / 10;
        $this->motion->z = $z / 10;
        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
    }

    private function avoidObstacles() : void {
        $direction = $this->getDirection();
        $pos = $this->getPosition()->add($direction->multiply(1));
        $block = $this->getLevel()->getBlock($pos);
        if($block->isSolid()){
            $this->motion->x = -$this->motion->x;
            $this->motion->z = -$this->motion->z;
            $this->move($this->motion->x, $this->motion->y, $this->motion->z);
        }
    }

    protected function updateTarget() : void {
        if($this->isInLove()) {
            $this->lookForMate();
        }
    }

    private function lookForMate() : void {
        foreach ($this->getLevel()->getEntities() as $entity) {
            if ($entity instanceof Sheep && $entity->isInLove() && $entity !== $this) {
                if ($this->distanceSquared($entity) < 10) {
                    $this->mateWith($entity);
                    break;
                }
            }
        }
    }

    private function mateWith(Sheep $mate) : void {
        $this->mateTimer++;
        if ($this->mateTimer > 20) {
            $this->mateTimer = 0;
            $this->giveBirth();
            $mate->giveBirth();
            $this->setInLove(false);
            $mate->setInLove(false);
        }
    }

    private function giveBirth() : void {
        $child = new Sheep($this->getLevel(), new CompoundTag());
        $child->setPosition($this->getPosition());
        $child->spawnToAll();
    }

    private function isInLove() : bool {
        return $this->inLove;
    }

    private function setInLove(bool $value = true) : void {
        $this->inLove = $value;
    }

    protected function updateInLove() : void {
        if ($this->isInLove()) {
            $this->level->addParticle(new HeartParticle($this));
        }
    }

    public function onUpdate(int $currentTick) : bool {
        if(!$this->isAlive()){
            return false;
        }

        $this->updateMovement();
        $this->updateTarget();
        $this->updateInLove();

        return parent::onUpdate($currentTick);
    }

    public function getDrops() : array {
        $drops = [];
        if (!$this->isBaby()) {
            $drops[] = Item::get(Item::WOOL, $this->getColor(), 1);
        }
        return $drops;
    }

    public function getXpDropAmount() : int {
        return $this->isBaby() ? 0 : mt_rand(1, 3);
    }
}
