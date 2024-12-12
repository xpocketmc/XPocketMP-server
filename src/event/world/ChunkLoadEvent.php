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

namespace pocketmine\event\world;

use pocketmine\world\format\Chunk;
use pocketmine\world\World;

/**
 * Called when a Chunk is loaded or newly created by the world generator.
 */
class ChunkLoadEvent extends ChunkEvent{
	public function __construct(
		World $world,
		int $chunkX,
		int $chunkZ,
		Chunk $chunk,
		private bool $newChunk
	){
		parent::__construct($world, $chunkX, $chunkZ, $chunk);
	}

	/**
	 * Returns whether the chunk is newly generated.
	 * If false, the chunk was loaded from storage.
	 */
	public function isNewChunk() : bool{
		return $this->newChunk;
	}
}
