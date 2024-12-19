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
 * Developed: ClousClouds, PMMP Team
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
 * @method static DripleafState FULL_TILT()
 * @method static DripleafState PARTIAL_TILT()
 * @method static DripleafState STABLE()
 * @method static DripleafState UNSTABLE()
 */
enum DripleafState{
	use LegacyEnumShimTrait;

	case STABLE;
	case UNSTABLE;
	case PARTIAL_TILT;
	case FULL_TILT;

	public function getScheduledUpdateDelayTicks() : ?int{
		return match($this){
			self::STABLE => null,
			self::UNSTABLE, self::PARTIAL_TILT => 10,
			self::FULL_TILT => 100,
		};
	}
}
