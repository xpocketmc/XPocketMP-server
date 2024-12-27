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
use pocketmine\world\generator\object\TreeType;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static SaplingType ACACIA()
 * @method static SaplingType BIRCH()
 * @method static SaplingType DARK_OAK()
 * @method static SaplingType JUNGLE()
 * @method static SaplingType OAK()
 * @method static SaplingType PALE_OAK()
 * @method static SaplingType SPRUCE()
 */
enum SaplingType{
	use LegacyEnumShimTrait;

	case OAK;
	case SPRUCE;
	case BIRCH;
	case JUNGLE;
	case ACACIA;
	case DARK_OAK;
	case PALE_OAK;
	//TODO: cherry

	public function getTreeType() : TreeType{
		return match($this){
			self::OAK => TreeType::OAK,
			self::SPRUCE => TreeType::SPRUCE,
			self::BIRCH => TreeType::BIRCH,
			self::JUNGLE => TreeType::JUNGLE,
			self::ACACIA => TreeType::ACACIA,
			self::DARK_OAK => TreeType::DARK_OAK,
			self::PALE_OAK => TreeType::PALE_OAK,
		};
	}

	public function getDisplayName() : string{
		return $this->getTreeType()->getDisplayName();
	}
}
