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

namespace pocketmine\block\utils;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static CopperOxidation EXPOSED()
 * @method static CopperOxidation NONE()
 * @method static CopperOxidation OXIDIZED()
 * @method static CopperOxidation WEATHERED()
 */
enum CopperOxidation : int{
	use LegacyEnumShimTrait;

	case NONE = 0;
	case EXPOSED = 1;
	case WEATHERED = 2;
	case OXIDIZED = 3;

	public function getPrevious() : ?self{
		return self::tryFrom($this->value - 1);
	}

	public function getNext() : ?self{
		return self::tryFrom($this->value + 1);
	}
}
