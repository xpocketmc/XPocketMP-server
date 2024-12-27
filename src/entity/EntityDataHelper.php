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

namespace pocketmine\entity;

use pocketmine\data\SavedDataLoadingException;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\world\World;
use function count;
use function is_infinite;
use function is_nan;

final class EntityDataHelper{

	private function __construct(){
		//NOOP
	}

	/**
	 * @throws SavedDataLoadingException
	 */
	private static function validateFloat(string $tagName, string $component, float $value) : void{
		if(is_infinite($value)){
			throw new SavedDataLoadingException("$component component of '$tagName' contains invalid infinite value");
		}
		if(is_nan($value)){
			throw new SavedDataLoadingException("$component component of '$tagName' contains invalid NaN value");
		}
	}

	/**
	 * @throws SavedDataLoadingException
	 */
	public static function parseLocation(CompoundTag $nbt, World $world) : Location{
		$pos = self::parseVec3($nbt, Entity::TAG_POS, false);

		$yawPitch = $nbt->getTag(Entity::TAG_ROTATION);
		if(!($yawPitch instanceof ListTag) || $yawPitch->getTagType() !== NBT::TAG_Float){
			throw new SavedDataLoadingException("'" . Entity::TAG_ROTATION . "' should be a List<Float>");
		}
		/** @var FloatTag[] $values */
		$values = $yawPitch->getValue();
		if(count($values) !== 2){
			throw new SavedDataLoadingException("Expected exactly 2 entries for 'Rotation'");
		}
		self::validateFloat(Entity::TAG_ROTATION, "yaw", $values[0]->getValue());
		self::validateFloat(Entity::TAG_ROTATION, "pitch", $values[1]->getValue());

		return Location::fromObject($pos, $world, $values[0]->getValue(), $values[1]->getValue());
	}

	/**
	 * @throws SavedDataLoadingException
	 */
	public static function parseVec3(CompoundTag $nbt, string $tagName, bool $optional) : Vector3{
		$pos = $nbt->getTag($tagName);
		if($pos === null && $optional){
			return Vector3::zero();
		}
		if(!($pos instanceof ListTag) || ($pos->getTagType() !== NBT::TAG_Double && $pos->getTagType() !== NBT::TAG_Float)){
			throw new SavedDataLoadingException("'$tagName' should be a List<Double> or List<Float>");
		}
		/** @var DoubleTag[]|FloatTag[] $values */
		$values = $pos->getValue();
		if(count($values) !== 3){
			throw new SavedDataLoadingException("Expected exactly 3 entries in '$tagName' tag");
		}

		$x = $values[0]->getValue();
		$y = $values[1]->getValue();
		$z = $values[2]->getValue();

		self::validateFloat($tagName, "x", $x);
		self::validateFloat($tagName, "y", $y);
		self::validateFloat($tagName, "z", $z);

		return new Vector3($x, $y, $z);
	}
}
