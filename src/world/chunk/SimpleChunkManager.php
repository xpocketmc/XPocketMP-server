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

namespace pocketmine\world\chunk;

use pocketmine\block\Block;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Limits;
use pocketmine\world\format\Chunk;
use pocketmine\world\World;

class SimpleChunkManager implements ChunkManager{

	/** @var Chunk[] */
	protected array $chunks = [];

	public function __construct(
		private int $minY,
		private int $maxY
	){}

	public function getBlockAt(int $x, int $y, int $z) : Block{
		if($this->isInWorld($x, $y, $z) && ($chunk = $this->getChunk($x >> Chunk::COORD_BIT_SIZE, $z >> Chunk::COORD_BIT_SIZE)) !== null){
			return RuntimeBlockStateRegistry::getInstance()->fromStateId($chunk->getBlockStateId($x & Chunk::COORD_MASK, $y, $z & Chunk::COORD_MASK));
		}
		return VanillaBlocks::AIR();
	}

	public function setBlockAt(int $x, int $y, int $z, Block $block) : void{
		if(($chunk = $this->getChunk($x >> Chunk::COORD_BIT_SIZE, $z >> Chunk::COORD_BIT_SIZE)) !== null){
			$chunk->setBlockStateId($x & Chunk::COORD_MASK, $y, $z & Chunk::COORD_MASK, $block->getStateId());
		}else{
			throw new \InvalidArgumentException("Cannot set block at coordinates x=$x,y=$y,z=$z, terrain is not loaded or out of bounds");
		}
	}

	public function getChunk(int $chunkX, int $chunkZ) : ?Chunk{
		return $this->chunks[World::chunkHash($chunkX, $chunkZ)] ?? null;
	}

	public function setChunk(int $chunkX, int $chunkZ, Chunk $chunk) : void{
		$this->chunks[World::chunkHash($chunkX, $chunkZ)] = $chunk;
	}

	public function cleanChunks() : void{
		$this->chunks = [];
	}

	public function getMinY() : int{
		return $this->minY;
	}

	public function getMaxY() : int{
		return $this->maxY;
	}

	public function isInWorld(int $x, int $y, int $z) : bool{
		return (
			$x <= Limits::INT32_MAX && $x >= Limits::INT32_MIN &&
			$y < $this->maxY && $y >= $this->minY &&
			$z <= Limits::INT32_MAX && $z >= Limits::INT32_MIN
		);
	}
}
