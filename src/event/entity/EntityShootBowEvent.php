<?php

/*
 *
 *  __  ______            _        _   __  __ ____
 *  \ \/ /  _ \ ___   ___| | _____| |_|  \/  |  _ \
 *   \  /| |_) / _ \ / __| |/ / _ \ __| |\/| | |_) |
 *   /  \|  __/ (_) | (__|   <  __/ |_| |  | |  __/
 *  /_/\_\_|   \___/ \___|_|\_\___|\__|_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\item\Item;
use function count;

/**
 * @phpstan-extends EntityEvent<Living>
 */
class EntityShootBowEvent extends EntityEvent implements Cancellable{
	use CancellableTrait;

	private Entity $projectile;

	public function __construct(
		Living $shooter,
		private Item $bow,
		Projectile $projectile,
		private float $force
	){
		$this->entity = $shooter;
		$this->projectile = $projectile;
	}

	/**
	 * @return Living
	 */
	public function getEntity(){
		return $this->entity;
	}

	public function getBow() : Item{
		return $this->bow;
	}

	/**
	 * Returns the entity considered as the projectile in this event.
	 *
	 * NOTE: This might not return a Projectile if a plugin modified the target entity.
	 */
	public function getProjectile() : Entity{
		return $this->projectile;
	}

	public function setProjectile(Entity $projectile) : void{
		if($projectile !== $this->projectile){
			if(count($this->projectile->getViewers()) === 0){
				$this->projectile->close();
			}
			$this->projectile = $projectile;
		}
	}

	public function getForce() : float{
		return $this->force;
	}

	public function setForce(float $force) : void{
		$this->force = $force;
	}
}
