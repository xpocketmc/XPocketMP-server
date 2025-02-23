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

namespace pocketmine\block;

use pocketmine\block\tile\Tile;
use pocketmine\utils\Utils;

class BlockIdentifier{
	/**
	 * @phpstan-param class-string<Tile>|null $tileClass
	 */
	public function __construct(
		private int $blockTypeId,
		private ?string $tileClass = null
	){
		if($blockTypeId < 0){
			throw new \InvalidArgumentException("Block type ID may not be negative");
		}
		if($tileClass !== null){
			Utils::testValidInstance($tileClass, Tile::class);
		}
	}

	public function getBlockTypeId() : int{ return $this->blockTypeId; }

	/**
	 * @phpstan-return class-string<Tile>|null
	 */
	public function getTileClass() : ?string{
		return $this->tileClass;
	}
}
