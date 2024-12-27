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

use pocketmine\entity\Living;

class HealthBoostEffect extends Effect{

	public function add(Living $entity, EffectInstance $instance) : void{
		$entity->setMaxHealth($entity->getMaxHealth() + 4 * $instance->getEffectLevel());
	}

	public function remove(Living $entity, EffectInstance $instance) : void{
		$entity->setMaxHealth($entity->getMaxHealth() - 4 * $instance->getEffectLevel());
		if($entity->getHealth() > $entity->getMaxHealth()){
			$entity->setHealth($entity->getMaxHealth());
		}
	}
}
