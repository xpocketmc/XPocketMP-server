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

use pocketmine\block\utils\MobHeadType;
use pocketmine\data\bedrock\MobHeadTypeIdMap;
use pocketmine\data\SavedDataLoadingException;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

/**
 * @deprecated
 * @see \pocketmine\block\MobHead
 */
class MobHead extends Spawnable{

	private const TAG_SKULL_TYPE = "SkullType"; //TAG_Byte
	private const TAG_ROT = "Rot"; //TAG_Byte
	private const TAG_MOUTH_MOVING = "MouthMoving"; //TAG_Byte
	private const TAG_MOUTH_TICK_COUNT = "MouthTickCount"; //TAG_Int

	private MobHeadType $mobHeadType = MobHeadType::SKELETON;
	private int $rotation = 0;

	public function readSaveData(CompoundTag $nbt) : void{
		if(($skullTypeTag = $nbt->getTag(self::TAG_SKULL_TYPE)) instanceof ByteTag){
			$mobHeadType = MobHeadTypeIdMap::getInstance()->fromId($skullTypeTag->getValue());
			if($mobHeadType === null){
				throw new SavedDataLoadingException("Invalid skull type tag value " . $skullTypeTag->getValue());
			}
			$this->mobHeadType = $mobHeadType;
		}
		$rotation = $nbt->getByte(self::TAG_ROT, 0);
		if($rotation >= 0 && $rotation <= 15){
			$this->rotation = $rotation;
		}
	}

	protected function writeSaveData(CompoundTag $nbt) : void{
		$nbt->setByte(self::TAG_SKULL_TYPE, MobHeadTypeIdMap::getInstance()->toId($this->mobHeadType));
		$nbt->setByte(self::TAG_ROT, $this->rotation);
	}

	public function setMobHeadType(MobHeadType $type) : void{
		$this->mobHeadType = $type;
	}

	public function getMobHeadType() : MobHeadType{
		return $this->mobHeadType;
	}

	public function getRotation() : int{
		return $this->rotation;
	}

	public function setRotation(int $rotation) : void{
		$this->rotation = $rotation;
	}

	protected function addAdditionalSpawnData(CompoundTag $nbt) : void{
		$nbt->setByte(self::TAG_SKULL_TYPE, MobHeadTypeIdMap::getInstance()->toId($this->mobHeadType));
		$nbt->setByte(self::TAG_ROT, $this->rotation);
	}
}
