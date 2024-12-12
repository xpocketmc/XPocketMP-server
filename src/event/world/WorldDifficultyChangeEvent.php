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

use pocketmine\world\World;

/**
 * Called when a world's difficulty is changed.
 */
final class WorldDifficultyChangeEvent extends WorldEvent{

	public function __construct(
		World $world,
		private int $oldDifficulty,
		private int $newDifficulty
	){
		parent::__construct($world);
	}

	public function getOldDifficulty() : int{ return $this->oldDifficulty; }

	public function getNewDifficulty() : int{ return $this->newDifficulty; }
}
