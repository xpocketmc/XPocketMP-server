<?php

namespace pocketmine\entity;

use pocketmine\block\BlockLegacyIds;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;
use pocketmine\level\particle\HeartParticle;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\world\World;

class Sheep extends Animal {

    protected float $width = 0.9;
    protected float $height = 1.3;
    protected bool $inLove = false;
    protected int $mateTimer = 0;

    public function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo($this->height, $this->width);
    }

    public function initEntity(CompoundTag $nbt): void {
        parent::initEntity($nbt);
        $this->setMaxHealth(8);
        $this->setHealth(8);
    }

    public function onInteract(Player $player, Vector3 $clickPos): bool {
        $item = $player->getInventory()->getItemInHand();
        if ($item->getId() === VanillaItems::SHEARS()->getId() && $this->isAlive()) {
            $this->level->addParticle(new HeartParticle($this));
            $this->kill();
            $this->level->dropItem($this, ItemFactory::get(VanillaItems::WOOL(), 0, mt_rand(1, 3)));
            return true;
        }
        return false;
    }

    protected function moveRandomly(): void {
        parent::moveRandomly();
    }

    protected function avoidObstacles(): void {
        parent::avoidObstacles();
    }

    protected function lookForMate(): void {
        parent::lookForMate();
    }

    protected function mateWith(Animal $mate): void {
        parent::mateWith($mate);
    }

    protected function giveBirth(): void {
        parent::giveBirth();
    }

    protected function isInLove(): bool {
        return $this->inLove;
    }

    protected function setInLove(bool $value = true): void {
        $this->inLove = $value;
    }

    protected function updateInLove(): void {
        parent::updateInLove();
    }

    public function saveNBT(): CompoundTag {
        $nbt = parent::saveNBT();
        $nbt->setInt("InLove", (int) $this->inLove);
        $nbt->setInt("MateTimer", $this->mateTimer);
        return $nbt;
    }

    public function loadNBT(CompoundTag $nbt): void {
        parent::loadNBT($nbt);
        $this->inLove = (bool) $nbt->getInt("InLove");
        $this->mateTimer = $nbt->getInt("MateTimer");
    }

    public function spawnTo(Player $player): void {
        parent::spawnTo($player);
        if ($this->isInLove()) {
            $this->getWorld()->addParticle(new Vector3($this->x, $this->y, $this->z), new HeartParticle($this));
        }
    }
}
