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
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\world\format\io;

use pocketmine\world\format\PalettedBlockArray;

die("This is a stub file for code completion purposes");

//TODO: this can't be moved right now because of compatibility issues with the extension
class SubChunkConverter{
	public static function convertSubChunkXZY(string $idArray, string $metaArray) : PalettedBlockArray{}

	public static function convertSubChunkYZX(string $idArray, string $metaArray) : PalettedBlockArray{}

	public static function convertSubChunkFromLegacyColumn(string $idArray, string $metaArray, int $yOffset) : PalettedBlockArray{}

}
