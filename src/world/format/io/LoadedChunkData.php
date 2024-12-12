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

/**
 * Encapsulates information returned when loading a chunk. This includes more information than saving a chunk, since the
 * data might have been upgraded or need post-processing.
 */
final class LoadedChunkData{
	public const FIXER_FLAG_NONE = 0;
	public const FIXER_FLAG_ALL = ~0;

	public function __construct(
		private ChunkData $data,
		private bool $upgraded,
		private int $fixerFlags
	){}

	public function getData() : ChunkData{ return $this->data; }

	public function isUpgraded() : bool{ return $this->upgraded; }

	public function getFixerFlags() : int{ return $this->fixerFlags; }
}
