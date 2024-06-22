<?php

namespace pocketmine\entity;

use pocketmine\block\Block;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\level\particle\HeartParticle;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\world\World;

abstract class Animal extends Living {

    protected bool $inLove = false;
    protected int $mateTimer = 0;

    public function initEntity(CompoundTag $nbt) : void {
        parent::initEntity($nbt);
    }

    public function onInteract(Player $player, Vector3 $clickPos) : bool {
        return false;
    }

    public function attack(EntityDamageEvent $source) : void {
        parent::attack($source);
    }

    protected function updateMovement(bool $teleport = false) : void {
        if($this->isInLove()) {
            $this->moveRandomly();
        } else {
            $this->avoidObstacles();
        }
        parent::updateMovement($teleport);
    }

    protected function updateTarget() : void {
        if($this->isInLove()) {
            $this->lookForMate();
        }
    }

    public function getDrops() : array {
        return [];
    }

    public function getXpDropAmount() : int {
        return 0;
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

    protected function moveRandomly() : void {
        $x = mt_rand(-10, 10);
        $z = mt_rand(-10, 10);
        $this->motion->x = $x / 10;
        $this->motion->z = $z / 10;
        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
    }

    protected function avoidObstacles() : void {
        $direction = $this->getDirection();
        $pos = $this->getPosition()->add($direction->x, $direction->y, $direction->z);
        $block = $this->getWorld()->getBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
        if($block->isSolid()){
            $this->motion->x = -$this->motion->x;
            $this->motion->z = -$this->motion->z;
            $this->move($this->motion->x, $this->motion->y, $this->motion->z);
        }
    }

    protected function lookForMate() : void {
        foreach ($this->getWorld()->getEntities() as $entity) {
            if ($entity instanceof self && $entity->isInLove() && $entity !== $this) {
                if ($this->distanceSquared($entity) < 10) {
                    $this->mateWith($entity);
                    break;
                }
            }
        }
    }

    protected function mateWith(Animal $mate) : void {
        $this->mateTimer++;
        if ($this->mateTimer > 20) {
            $this->mateTimer = 0;
            $this->giveBirth();
            $mate->giveBirth();
            $this->setInLove(false);
            $mate->setInLove(false);
        }
    }

    protected function giveBirth() : void {
        $child = new static($this->getWorld(), new CompoundTag());
        $child->setPosition($this->getPosition());
        $child->spawnToAll();
    }

    protected function isInLove() : bool {
        return $this->inLove;
    }

    protected function setInLove(bool $value = true) : void {
        $this->inLove = $value;
    }

    protected function updateInLove() : void {
        if ($this->isInLove()) {
            $this->getWorld()->addParticle(new Vector3($this->x, $this->y, $this->z), new HeartParticle($this));
        }
    }

    public function onCollideWithPlayer(Player $player) : void {
        if($player->isCreative() || $player->isSpectator()){
            return;
        }

        if($this->isInLove()){
            $this->mateWith($this);
        }
    }

    public function saveNBT() : CompoundTag {
        $nbt = parent::saveNBT();
        $nbt->setInt("InLove", (int) $this->inLove);
        $nbt->setInt("MateTimer", $this->mateTimer);
        return $nbt;
    }

    public function loadNBT(CompoundTag $nbt) : void {
        parent::loadNBT($nbt);
        $this->inLove = (bool) $nbt->getInt("InLove");
        $this->mateTimer = $nbt->getInt("MateTimer");
    }

    public function spawnTo(Player $player) : void {
        parent::spawnTo($player);
        if ($this->isInLove()) {
            $this->getWorld()->addParticle(new Vector3($this->x, $this->y, $this->z), new HeartParticle($this));
        }
    }
}
