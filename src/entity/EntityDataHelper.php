<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author XPocketMP Team
 * @link http://www.xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace XPocketMP\entity;

use XPocketMP\data\SavedDataLoadingException;
use XPocketMP\math\Vector3;
use XPocketMP\nbt\NBT;
use XPocketMP\nbt\tag\CompoundTag;
use XPocketMP\nbt\tag\DoubleTag;
use XPocketMP\nbt\tag\FloatTag;
use XPocketMP\nbt\tag\ListTag;
use XPocketMP\world\World;
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