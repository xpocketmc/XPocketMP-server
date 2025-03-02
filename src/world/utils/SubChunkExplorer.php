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

namespace pocketmine\world\utils;

use pocketmine\world\chunk\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\SubChunk;

class SubChunkExplorer{
	public ?Chunk $currentChunk = null;
	public ?SubChunk $currentSubChunk = null;

	protected int $currentX;
	protected int $currentY;
	protected int $currentZ;

	public function __construct(
		protected ChunkManager $world
	){}

	/**
	 * @phpstan-return SubChunkExplorerStatus::*
	 */
	public function moveTo(int $x, int $y, int $z) : int{
		$newChunkX = $x >> SubChunk::COORD_BIT_SIZE;
		$newChunkZ = $z >> SubChunk::COORD_BIT_SIZE;
		if($this->currentChunk === null || $this->currentX !== $newChunkX || $this->currentZ !== $newChunkZ){
			$this->currentX = $newChunkX;
			$this->currentZ = $newChunkZ;
			$this->currentSubChunk = null;

			$this->currentChunk = $this->world->getChunk($this->currentX, $this->currentZ);
			if($this->currentChunk === null){
				return SubChunkExplorerStatus::INVALID;
			}
		}

		$newChunkY = $y >> SubChunk::COORD_BIT_SIZE;
		if($this->currentSubChunk === null || $this->currentY !== $newChunkY){
			$this->currentY = $newChunkY;

			if($this->currentY < Chunk::MIN_SUBCHUNK_INDEX || $this->currentY > Chunk::MAX_SUBCHUNK_INDEX){
				$this->currentSubChunk = null;
				return SubChunkExplorerStatus::INVALID;
			}

			$this->currentSubChunk = $this->currentChunk->getSubChunk($newChunkY);
			return SubChunkExplorerStatus::MOVED;
		}

		return SubChunkExplorerStatus::OK;
	}

	/**
	 * @phpstan-return SubChunkExplorerStatus::*
	 */
	public function moveToChunk(int $chunkX, int $chunkY, int $chunkZ) : int{
		//this is a cold path, so we don't care much if it's a bit slower (extra fcall overhead)
		return $this->moveTo($chunkX << SubChunk::COORD_BIT_SIZE, $chunkY << SubChunk::COORD_BIT_SIZE, $chunkZ << SubChunk::COORD_BIT_SIZE);
	}

	/**
	 * Returns whether we currently have a valid terrain pointer.
	 */
	public function isValid() : bool{
		return $this->currentSubChunk !== null;
	}

	public function invalidate() : void{
		$this->currentChunk = null;
		$this->currentSubChunk = null;
	}
}
