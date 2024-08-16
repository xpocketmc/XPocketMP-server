<?php

namespace pocketmine\entity;

use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\nbt\tag\CompoundTag;

abstract class Monster extends Living {

    protected ?Player $target = null;

    public function initEntity(CompoundTag $nbt): void {
        parent::initEntity($nbt);
    }

    public function getName(): string {
        return "Monster";
    }

    public function setTarget(?Player $target): void {
        $this->target = $target;
    }

    public function getTarget(): ?Player {
        return $this->target;
    }

    public function onUpdate(int $currentTick): bool {
        if ($this->isClosed()) {
            return false;
        }

        $hasUpdate = parent::onUpdate($currentTick);

        if ($this->target !== null && $this->target->isAlive()) {
            $this->moveTowardsTarget();
        } else {
            $this->findNewTarget();
        }

        return $hasUpdate;
    }

    protected function moveTowardsTarget(): void {
        if ($this->target instanceof Player) {
            $targetPos = $this->target->getPosition();
            $dir = $targetPos->subtract($this->location)->normalize();

            $this->motion->x = $dir->x * 0.2; 
            $this->motion->z = $dir->z * 0.2;

            if ($this->onGround && ($this->getWorld()->getBlock($this->location->add(0, -1, 0))->getId() !== 0)) {
                $this->motion->y = 0.42;
            }

            $this->move($this->motion->x, $this->motion->y, $this->motion->z);
        }
    }

    protected function findNewTarget(): void {
        // Cari pemain terdekat dan tetapkan sebagai target
        $nearestPlayer = null;
        $nearestDistance = PHP_FLOAT_MAX;

        foreach ($this->getWorld()->getPlayers() as $player) {
            $distance = $this->distanceSquared($player);
            if ($distance < $nearestDistance) {
                $nearestDistance = $distance;
                $nearestPlayer = $player;
            }
        }

        if ($nearestPlayer !== null && $nearestDistance <= 256) {
            $this->setTarget($nearestPlayer);
        } else {
            $this->setTarget(null);
        }
    }

    public function attackEntity(Player $player): void {
        if ($this->distanceSquared($player) <= 2) {
            $damage = 5;
            $player->attack(new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $damage));
        }
    }
}
