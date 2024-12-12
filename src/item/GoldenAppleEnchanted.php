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

namespace pocketmine\item;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;

class GoldenAppleEnchanted extends GoldenApple{

	public function getAdditionalEffects() : array{
		return [
			new EffectInstance(VanillaEffects::REGENERATION(), 600, 1),
			new EffectInstance(VanillaEffects::ABSORPTION(), 2400, 3),
			new EffectInstance(VanillaEffects::RESISTANCE(), 6000),
			new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 6000)
		];
	}
}
