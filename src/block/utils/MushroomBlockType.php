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

namespace pocketmine\block\utils;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static MushroomBlockType ALL_CAP()
 * @method static MushroomBlockType CAP_EAST()
 * @method static MushroomBlockType CAP_MIDDLE()
 * @method static MushroomBlockType CAP_NORTH()
 * @method static MushroomBlockType CAP_NORTHEAST()
 * @method static MushroomBlockType CAP_NORTHWEST()
 * @method static MushroomBlockType CAP_SOUTH()
 * @method static MushroomBlockType CAP_SOUTHEAST()
 * @method static MushroomBlockType CAP_SOUTHWEST()
 * @method static MushroomBlockType CAP_WEST()
 * @method static MushroomBlockType PORES()
 */
enum MushroomBlockType{
	use LegacyEnumShimTrait;

	case PORES;
	case CAP_NORTHWEST;
	case CAP_NORTH;
	case CAP_NORTHEAST;
	case CAP_WEST;
	case CAP_MIDDLE;
	case CAP_EAST;
	case CAP_SOUTHWEST;
	case CAP_SOUTH;
	case CAP_SOUTHEAST;
	case ALL_CAP;
}
