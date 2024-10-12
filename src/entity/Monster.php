<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use function sqrt;

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
