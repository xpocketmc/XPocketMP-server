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

namespace pocketmine\block\tile;

use pocketmine\nbt\tag\CompoundTag;

final class Beacon extends Spawnable{
	private const TAG_PRIMARY = "primary"; //TAG_Int
	private const TAG_SECONDARY = "secondary"; //TAG_Int

	private int $primaryEffect = 0;
	private int $secondaryEffect = 0;

	protected function addAdditionalSpawnData(CompoundTag $nbt) : void{
		$nbt->setInt(self::TAG_PRIMARY, $this->primaryEffect);
		$nbt->setInt(self::TAG_SECONDARY, $this->secondaryEffect);
	}

	public function readSaveData(CompoundTag $nbt) : void{
		//TODO: PC uses Primary and Secondary (capitalized first letter), we don't read them here because the IDs would be different
		$this->primaryEffect = $nbt->getInt(self::TAG_PRIMARY, 0);
		$this->secondaryEffect = $nbt->getInt(self::TAG_SECONDARY, 0);
	}

	protected function writeSaveData(CompoundTag $nbt) : void{
		$nbt->setInt(self::TAG_PRIMARY, $this->primaryEffect);
		$nbt->setInt(self::TAG_SECONDARY, $this->secondaryEffect);
	}

	public function getPrimaryEffect() : int{ return $this->primaryEffect; }

	public function setPrimaryEffect(int $primaryEffect) : void{ $this->primaryEffect = $primaryEffect; }

	public function getSecondaryEffect() : int{ return $this->secondaryEffect; }

	public function setSecondaryEffect(int $secondaryEffect) : void{ $this->secondaryEffect = $secondaryEffect; }
}
