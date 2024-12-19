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

namespace pocketmine\world;

use pocketmine\math\Vector3;
use pocketmine\world\format\Chunk;

/**
 * This trait implements no-op default methods for chunk listeners.
 * @see ChunkListener
 */
trait ChunkListenerNoOpTrait/* implements ChunkListener*/{

	public function onChunkChanged(int $chunkX, int $chunkZ, Chunk $chunk) : void{
		//NOOP
	}

	public function onChunkLoaded(int $chunkX, int $chunkZ, Chunk $chunk) : void{
		//NOOP
	}

	public function onChunkUnloaded(int $chunkX, int $chunkZ, Chunk $chunk) : void{
		//NOOP
	}

	public function onChunkPopulated(int $chunkX, int $chunkZ, Chunk $chunk) : void{
		//NOOP
	}

	public function onBlockChanged(Vector3 $block) : void{
		//NOOP
	}
}
