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

use pocketmine\data\bedrock\block\BlockLegacyMetadata;
use pocketmine\math\Facing;

final class RailConnectionInfo{

	public const FLAG_ASCEND = 1 << 24; //used to indicate direction-up

	public const CONNECTIONS = [
		//straights
		BlockLegacyMetadata::RAIL_STRAIGHT_NORTH_SOUTH => [
			Facing::NORTH,
			Facing::SOUTH
		],
		BlockLegacyMetadata::RAIL_STRAIGHT_EAST_WEST => [
			Facing::EAST,
			Facing::WEST
		],

		//ascending
		BlockLegacyMetadata::RAIL_ASCENDING_EAST => [
			Facing::WEST,
			Facing::EAST | self::FLAG_ASCEND
		],
		BlockLegacyMetadata::RAIL_ASCENDING_WEST => [
			Facing::EAST,
			Facing::WEST | self::FLAG_ASCEND
		],
		BlockLegacyMetadata::RAIL_ASCENDING_NORTH => [
			Facing::SOUTH,
			Facing::NORTH | self::FLAG_ASCEND
		],
		BlockLegacyMetadata::RAIL_ASCENDING_SOUTH => [
			Facing::NORTH,
			Facing::SOUTH | self::FLAG_ASCEND
		]
	];

	/* extended meta values for regular rails, to allow curving */
	public const CURVE_CONNECTIONS = [
		BlockLegacyMetadata::RAIL_CURVE_SOUTHEAST => [
			Facing::SOUTH,
			Facing::EAST
		],
		BlockLegacyMetadata::RAIL_CURVE_SOUTHWEST => [
			Facing::SOUTH,
			Facing::WEST
		],
		BlockLegacyMetadata::RAIL_CURVE_NORTHWEST => [
			Facing::NORTH,
			Facing::WEST
		],
		BlockLegacyMetadata::RAIL_CURVE_NORTHEAST => [
			Facing::NORTH,
			Facing::EAST
		]
	];
}
