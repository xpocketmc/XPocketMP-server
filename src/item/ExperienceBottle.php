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

namespace pocketmine\item;

use pocketmine\entity\EntityFactory;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\ExperienceBottle as ExperienceBottleEntity;
use pocketmine\entity\projectile\Throwable;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class ExperienceBottle extends ProjectileItem{

	protected function createEntity(EntityFactory $factory, Location $location, Vector3 $velocity, Player $thrower) : Throwable{
		/** @var ExperienceBottleEntity $projectile */
		$projectile = $factory->create(
			ExperienceBottleEntity::class,
			$location->getWorldNonNull(),
			EntityFactory::createBaseNBT($location, $velocity, $location->yaw, $location->pitch),
			$thrower
		);
		return $projectile;
	}

	public function getThrowForce() : float{
		return 0.7;
	}
}
