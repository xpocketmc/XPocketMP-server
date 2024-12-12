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
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\entity\projectile;

use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\particle\PotionSplashParticle;
use pocketmine\world\sound\PotionSplashSound;
use function mt_rand;

class ExperienceBottle extends Throwable{
	public static function getNetworkTypeId() : string{ return EntityIds::XP_BOTTLE; }

	protected function getInitialGravity() : float{ return 0.07; }

	public function getResultDamage() : int{
		return -1;
	}

	public function onHit(ProjectileHitEvent $event) : void{
		$this->getWorld()->addParticle($this->location, new PotionSplashParticle(PotionSplashParticle::DEFAULT_COLOR()));
		$this->broadcastSound(new PotionSplashSound());

		$this->getWorld()->dropExperience($this->location, mt_rand(3, 11));
	}
}
