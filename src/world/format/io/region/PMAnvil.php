<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author XPocketMP Team
 * @link http://www.xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace XPocketMP\world\format\io\region;

use XPocketMPlock\Block;
use XPocketMP\nbt\tag\CompoundTag;
use XPocketMP\world\format\PalettedBlockArray;
use XPocketMP\world\format\SubChunk;

/**
 * This format is exactly the same as the PC Anvil format, with the only difference being that the stored data order
 * is XZY instead of YZX for more performance loading and saving worlds.
 */
class PMAnvil extends RegionWorldProvider{
	use LegacyAnvilChunkTrait;

	protected function deserializeSubChunk(CompoundTag $subChunk, PalettedBlockArray $biomes3d, \Logger $logger) : SubChunk{
		return new SubChunk(Block::EMPTY_STATE_ID, [$this->palettizeLegacySubChunkXZY(
			self::readFixedSizeByteArray($subChunk, "Blocks", 4096),
			self::readFixedSizeByteArray($subChunk, "Data", 2048),
			$logger
		)], $biomes3d);
	}

	protected static function getRegionFileExtension() : string{
		return "mcapm";
	}

	protected static function getPcWorldFormatVersion() : int{
		return -1; //Not a PC format, only XPocketMP-MP
	}

	public function getWorldMinY() : int{
		return 0;
	}

	public function getWorldMaxY() : int{
		return 256;
	}
}