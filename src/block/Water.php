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
 * the Free Software Foundation
 * The files in XPocketMP are mostly from PocketMine-MP.
 * Developed by ClousClouds, PMMP Team
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\world\sound\BucketEmptyWaterSound;
use pocketmine\world\sound\BucketFillWaterSound;
use pocketmine\world\sound\Sound;

class Water extends Liquid{

	public function getLightFilter() : int{
		return 2;
	}

	public function getBucketFillSound() : Sound{
		return new BucketFillWaterSound();
	}

	public function getBucketEmptySound() : Sound{
		return new BucketEmptyWaterSound();
	}

	public function tickRate() : int{
		return 5;
	}

	public function getMinAdjacentSourcesToFormSource() : ?int{
		return 2;
	}

	public function onEntityInside(Entity $entity) : bool{
		$entity->resetFallDistance();
		if($entity->isOnFire()){
			$entity->extinguish();
		}
		return true;
	}
}
