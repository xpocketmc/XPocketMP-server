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
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\block\tile;

use pocketmine\block\Note as BlockNote;
use pocketmine\nbt\tag\CompoundTag;

/**
 * @deprecated
 */
class Note extends Tile{
	private int $pitch = 0;

	public function readSaveData(CompoundTag $nbt) : void{
		if(($pitch = $nbt->getByte("note", $this->pitch)) > BlockNote::MIN_PITCH && $pitch <= BlockNote::MAX_PITCH){
			$this->pitch = $pitch;
		}
	}

	protected function writeSaveData(CompoundTag $nbt) : void{
		$nbt->setByte("note", $this->pitch);
	}

	public function getPitch() : int{
		return $this->pitch;
	}

	public function setPitch(int $pitch) : void{
		if($pitch < BlockNote::MIN_PITCH || $pitch > BlockNote::MAX_PITCH){
			throw new \InvalidArgumentException("Pitch must be in range " . BlockNote::MIN_PITCH . " - " . BlockNote::MAX_PITCH);
		}
		$this->pitch = $pitch;
	}
}
