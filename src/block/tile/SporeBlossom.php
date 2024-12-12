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

namespace pocketmine\block\tile;

use pocketmine\nbt\tag\CompoundTag;

/**
 * This exists to force the client to update the spore blossom every tick, which is necessary for it to generate
 * particles.
 */
final class SporeBlossom extends Spawnable{

	protected function addAdditionalSpawnData(CompoundTag $nbt) : void{
		//NOOP
	}

	public function readSaveData(CompoundTag $nbt) : void{
		//NOOP
	}

	protected function writeSaveData(CompoundTag $nbt) : void{
		//NOOP
	}
}
