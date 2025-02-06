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

namespace pocketmine\world;

use pocketmine\math\Vector3;
use pocketmine\world\format\Chunk;

/**
 * This interface allows you to listen for events occurring on or in specific chunks. This will receive events for any
 * chunks which it is registered to listen to.
 *
 * @see World::registerChunkListener()
 * @see World::unregisterChunkListener()
 *
 * WARNING: When you're done with the listener, make sure you unregister it from all chunks it's listening to, otherwise
 * the object will not be destroyed.
 * The listener WILL NOT be unregistered when chunks are unloaded. You need to do this yourself when you're done with
 * a chunk.
 */
interface ChunkListener{

	/**
	 * This method will be called when a Chunk is replaced by a new one
	 */
	public function onChunkChanged(int $chunkX, int $chunkZ, Chunk $chunk) : void;

	/**
	 * This method will be called when a registered chunk is loaded
	 */
	public function onChunkLoaded(int $chunkX, int $chunkZ, Chunk $chunk) : void;

	/**
	 * This method will be called when a registered chunk is unloaded
	 */
	public function onChunkUnloaded(int $chunkX, int $chunkZ, Chunk $chunk) : void;

	/**
	 * This method will be called when a registered chunk is populated
	 * Usually it'll be sent with another call to onChunkChanged()
	 */
	public function onChunkPopulated(int $chunkX, int $chunkZ, Chunk $chunk) : void;

	/**
	 * This method will be called when a block changes in a registered chunk
	 */
	public function onBlockChanged(Vector3 $block) : void;
}
