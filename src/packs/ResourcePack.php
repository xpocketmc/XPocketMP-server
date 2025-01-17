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

namespace pocketmine\packs;

interface ResourcePack{

	/**
	 * Returns the human-readable name of the resource pack
	 */
	public function getPackName() : string;

	/**
	 * Returns the pack's UUID as a human-readable string
	 */
	public function getPackId() : string;

	/**
	 * Returns the size of the pack on disk in bytes.
	 */
	public function getPackSize() : int;

	/**
	 * Returns a version number for the pack in the format major.minor.patch
	 */
	public function getPackVersion() : string;

	/**
	 * Returns the raw SHA256 sum of the compressed resource pack zip. This is used by clients to validate pack downloads.
	 * @return string byte-array length 32 bytes
	 */
	public function getSha256() : string;

	/**
	 * Returns a chunk of the resource pack zip as a byte-array for sending to clients.
	 *
	 * Note that resource packs must **always** be in zip archive format for sending.
	 * A folder resource loader may need to perform on-the-fly compression for this purpose.
	 *
	 * @param int $start  Offset to start reading the chunk from
	 * @param int $length Maximum length of data to return.
	 *
	 * @phpstan-param positive-int $length
	 *
	 * @return string byte-array
	 * @throws \InvalidArgumentException if the chunk does not exist
	 */
	public function getPackChunk(int $start, int $length) : string;
}
