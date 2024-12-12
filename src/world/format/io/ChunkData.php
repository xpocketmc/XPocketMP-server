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

namespace pocketmine\world\format\io;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\format\SubChunk;

final class ChunkData{

	/**
	 * @param SubChunk[]    $subChunks
	 * @param CompoundTag[] $entityNBT
	 * @param CompoundTag[] $tileNBT
	 *
	 * @phpstan-param array<int, SubChunk> $subChunks
	 * @phpstan-param list<CompoundTag> $entityNBT
	 * @phpstan-param list<CompoundTag> $tileNBT
	 */
	public function __construct(
		private array $subChunks,
		private bool $populated,
		private array $entityNBT,
		private array $tileNBT
	){}

	/**
	 * @return SubChunk[]
	 * @phpstan-return array<int, SubChunk>
	 */
	public function getSubChunks() : array{ return $this->subChunks; }

	public function isPopulated() : bool{ return $this->populated; }

	/**
	 * @return CompoundTag[]
	 * @phpstan-return list<CompoundTag>
	 */
	public function getEntityNBT() : array{ return $this->entityNBT; }

	/**
	 * @return CompoundTag[]
	 * @phpstan-return list<CompoundTag>
	 */
	public function getTileNBT() : array{ return $this->tileNBT; }
}
