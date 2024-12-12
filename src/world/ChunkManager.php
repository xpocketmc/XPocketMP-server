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

namespace pocketmine\world;

use pocketmine\block\Block;
use pocketmine\world\format\Chunk;

interface ChunkManager{

	/**
	 * Returns a Block object representing the block state at the given coordinates.
	 */
	public function getBlockAt(int $x, int $y, int $z) : Block;

	/**
	 * Sets the block at the given coordinates to the block state specified.
	 *
	 * @throws \InvalidArgumentException
	 */
	public function setBlockAt(int $x, int $y, int $z, Block $block) : void;

	public function getChunk(int $chunkX, int $chunkZ) : ?Chunk;

	public function setChunk(int $chunkX, int $chunkZ, Chunk $chunk) : void;

	/**
	 * Returns the lowest buildable Y coordinate of the world
	 */
	public function getMinY() : int;

	/**
	 * Returns the highest buildable Y coordinate of the world
	 */
	public function getMaxY() : int;

	/**
	 * Returns whether the specified coordinates are within the valid world boundaries, taking world format limitations
	 * into account.
	 */
	public function isInWorld(int $x, int $y, int $z) : bool;
}
