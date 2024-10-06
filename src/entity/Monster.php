<?php

namespace pocketmine\entity;

use pocketmine\entity\Living;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;

abstract class Monster extends Living {

	protected function initEntity(CompoundTag $nbt) : void {
		parent::initEntity($nbt);
		$this->setMaxHealth(20);
		$this->setMovementSpeed(1.0);
	}

	public function chasePlayer(Player $player) : void {
		$this->lookAt($player->getPosition());
		$direction = new Vector3(
			$player->getPosition()->getX() - $this->getPosition()->getX(),
			$player->getPosition()->getY() - $this->getPosition()->getY(),
			$player->getPosition()->getZ() - $this->getPosition()->getZ()
		);
		$direction = $direction->normalize();
		$this->setMotion($direction->multiply($this->getMovementSpeed()));
	}

	public function attackPlayer(Player $player) : void {
		$distance = sqrt($this->getPosition()->distanceSquared($player->getPosition()));
		if ($distance <= 1.5) {
			$player->setHealth($player->getHealth() - 2);
		}
	}

	public function onUpdate(int $currentTick) : bool {
		$nearestPlayer = $this->getWorld()->getNearestEntity($this->getPosition(), 10, Player::class);
		if ($nearestPlayer instanceof Player) {
			$this->chasePlayer($nearestPlayer);
			$this->attackPlayer($nearestPlayer);
		}
		return parent::onUpdate($currentTick);
	}
}
