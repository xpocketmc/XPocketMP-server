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

namespace pocketmine\entity\effect;

use pocketmine\entity\Living;

class SlownessEffect extends Effect{

	public function add(Living $entity, EffectInstance $instance) : void{
		$entity->setMovementSpeed($entity->getMovementSpeed() * (1 - 0.15 * $instance->getEffectLevel()), true);
	}

	public function remove(Living $entity, EffectInstance $instance) : void{
		$entity->setMovementSpeed($entity->getMovementSpeed() / (1 - 0.15 * $instance->getEffectLevel()));
	}
}
