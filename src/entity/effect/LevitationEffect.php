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

namespace pocketmine\entity\effect;

use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\player\Player;

class LevitationEffect extends Effect{

	public function canTick(EffectInstance $instance) : bool{
		return true;
	}

	public function applyEffect(Living $entity, EffectInstance $instance, float $potency = 1.0, ?Entity $source = null) : void{
		if(!($entity instanceof Player)){ //TODO: ugly hack, player motion isn't updated properly by the server yet :(
			$entity->addMotion(0, ($instance->getEffectLevel() / 20 - $entity->getMotion()->y) / 5, 0);
		}
	}

	public function add(Living $entity, EffectInstance $instance) : void{
		$entity->setHasGravity(false);
	}

	public function remove(Living $entity, EffectInstance $instance) : void{
		$entity->setHasGravity();
	}
}
