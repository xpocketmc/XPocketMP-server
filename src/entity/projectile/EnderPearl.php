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

namespace pocketmine\entity\projectile;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\particle\EndermanTeleportParticle;
use pocketmine\world\sound\EndermanTeleportSound;

class EnderPearl extends Throwable{
	public static function getNetworkTypeId() : string{ return EntityIds::ENDER_PEARL; }

	protected function onHit(ProjectileHitEvent $event) : void{
		$owner = $this->getOwningEntity();
		if($owner !== null){
			//TODO: check end gateways (when they are added)
			//TODO: spawn endermites at origin

			$this->getWorld()->addParticle($origin = $owner->getPosition(), new EndermanTeleportParticle());
			$this->getWorld()->addSound($origin, new EndermanTeleportSound());
			$owner->teleport($target = $event->getRayTraceResult()->getHitVector());
			$this->getWorld()->addSound($target, new EndermanTeleportSound());

			$owner->attack(new EntityDamageEvent($owner, EntityDamageEvent::CAUSE_FALL, 5));
		}
	}
}
