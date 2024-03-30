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

namespace pocketmine\world;

use pocketmine\block\Block;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\block\utils\Waterloggable;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\Water;
use pocketmine\utils\Limits;
use pocketmine\world\format\Chunk;

class SimpleChunkManager implements ChunkManager{

	/** @var Chunk[] */
	protected array $chunks = [];

	public function __construct(
		private int $minY,
		private int $maxY
	){}

	public function getBlockAt(int $x, int $y, int $z) : Block{
		if($this->isInWorld($x, $y, $z) && ($chunk = $this->getChunk($x >> Chunk::COORD_BIT_SIZE, $z >> Chunk::COORD_BIT_SIZE)) !== null){
			$xMasked = $x & Chunk::COORD_MASK;
			$zMasked = $z & Chunk::COORD_MASK;

			$block = RuntimeBlockStateRegistry::getInstance()->fromStateId($chunk->getBlockStateId($xMasked, $y, $zMasked));
			if($block instanceof Waterloggable){
				$blockState = RuntimeBlockStateRegistry::getInstance()->fromStateId($chunk->getBlockWaterlogged($xMasked, $y, $zMasked));
				if($blockState instanceof Water){
					$block->setWaterLogging($blockState);
				}
			}

			return $block;
		}
		return VanillaBlocks::AIR();
	}

	public function setBlockAt(int $x, int $y, int $z, Block $block) : void{
		if(($chunk = $this->getChunk($x >> Chunk::COORD_BIT_SIZE, $z >> Chunk::COORD_BIT_SIZE)) !== null){
			$xMasked = $x & Chunk::COORD_MASK;
			$zMasked = $z & Chunk::COORD_MASK;

			$chunk->setBlockStateId($xMasked, $y, $zMasked, $block->getStateId());
			if($block instanceof Waterloggable && $block->getWaterLogging() !== null){
				$chunk->setBlockWaterlogged($xMasked, $y, $zMasked, $block->getWaterLogging()->getStateId());
			}else{
				$chunk->setBlockWaterlogged($xMasked, $y, $zMasked, null);
			}
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
