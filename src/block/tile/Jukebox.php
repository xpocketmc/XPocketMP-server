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

use pocketmine\item\Item;
use pocketmine\item\Record;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\world\sound\RecordStopSound;

class Jukebox extends Spawnable{
	private const TAG_RECORD = "RecordItem"; //Item CompoundTag

	private ?Record $record = null;

	public function getRecord() : ?Record{
		return $this->record;
	}

	public function setRecord(?Record $record) : void{
		$this->record = $record;
	}

	public function readSaveData(CompoundTag $nbt) : void{
		if(($tag = $nbt->getCompoundTag(self::TAG_RECORD)) !== null){
			$record = Item::nbtDeserialize($tag);
			if($record instanceof Record){
				$this->record = $record;
			}
		}
	}

	protected function writeSaveData(CompoundTag $nbt) : void{
		if($this->record !== null){
			$nbt->setTag(self::TAG_RECORD, $this->record->nbtSerialize());
		}
	}

	protected function addAdditionalSpawnData(CompoundTag $nbt) : void{
		//this is needed for the note particles to show on the client side
		if($this->record !== null){
			$nbt->setTag(self::TAG_RECORD, TypeConverter::getInstance()->getItemTranslator()->toNetworkNbt($this->record));
		}
	}

	protected function onBlockDestroyedHook() : void{
		$this->position->getWorld()->addSound($this->position, new RecordStopSound());
	}
}
