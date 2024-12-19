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
 * @method static LeavesType ACACIA()
 * @method static LeavesType AZALEA()
 * @method static LeavesType BIRCH()
 * @method static LeavesType CHERRY()
 * @method static LeavesType DARK_OAK()
 * @method static LeavesType FLOWERING_AZALEA()
 * @method static LeavesType JUNGLE()
 * @method static LeavesType MANGROVE()
 * @method static LeavesType OAK()
 * @method static LeavesType PALE_OAK()
 * @method static LeavesType SPRUCE()
 */
enum LeavesType{
	use LegacyEnumShimTrait;

	case OAK;
	case SPRUCE;
	case BIRCH;
	case JUNGLE;
	case ACACIA;
	case DARK_OAK;
	case MANGROVE;
	case AZALEA;
	case FLOWERING_AZALEA;
	case CHERRY;
	case PALE_OAK;

	public function getDisplayName() : string{
		return match($this){
			self::OAK => "Oak",
			self::SPRUCE => "Spruce",
			self::BIRCH => "Birch",
			self::JUNGLE => "Jungle",
			self::ACACIA => "Acacia",
			self::DARK_OAK => "Dark Oak",
			self::MANGROVE => "Mangrove",
			self::AZALEA => "Azalea",
			self::FLOWERING_AZALEA => "Flowering Azalea",
			self::CHERRY => "Cherry",
			self::PALE_OAK => "Pale Oak",
		};
	}
}
