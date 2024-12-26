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
 * @method static MobHeadType CREEPER()
 * @method static MobHeadType DRAGON()
 * @method static MobHeadType PIGLIN()
 * @method static MobHeadType PLAYER()
 * @method static MobHeadType SKELETON()
 * @method static MobHeadType WITHER_SKELETON()
 * @method static MobHeadType ZOMBIE()
 */
enum MobHeadType{
	use LegacyEnumShimTrait;

	case SKELETON;
	case WITHER_SKELETON;
	case ZOMBIE;
	case PLAYER;
	case CREEPER;
	case DRAGON;
	case PIGLIN;

	public function getDisplayName() : string{
		return match($this){
			self::SKELETON => "Skeleton Skull",
			self::WITHER_SKELETON => "Wither Skeleton Skull",
			self::ZOMBIE => "Zombie Head",
			self::PLAYER => "Player Head",
			self::CREEPER => "Creeper Head",
			self::DRAGON => "Dragon Head",
			self::PIGLIN => "Piglin Head"
		};
	}
}
