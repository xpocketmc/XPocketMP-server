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

namespace XPocketMPlock\tile;

use XPocketMPlock\utils\BannerPatternLayer;
use XPocketMPlock\utils\DyeColor;
use XPocketMP\data\bedrock\BannerPatternTypeIdMap;
use XPocketMP\data\bedrock\DyeColorIdMap;
use XPocketMP\nbt\tag\CompoundTag;
use XPocketMP\nbt\tag\IntTag;
use XPocketMP\nbt\tag\ListTag;

/**
 * @deprecated
 * @see \XPocketMPlock\BaseBanner
 */
class Banner extends Spawnable{

	public const TAG_BASE = "Base";
	public const TAG_PATTERNS = "Patterns";
	public const TAG_PATTERN_COLOR = "Color";
	public const TAG_PATTERN_NAME = "Pattern";

	private DyeColor $baseColor = DyeColor::BLACK;

	/**
	 * @var BannerPatternLayer[]
	 * @phpstan-var list<BannerPatternLayer>
	 */
	private array $patterns = [];

	public function readSaveData(CompoundTag $nbt) : void{
		$colorIdMap = DyeColorIdMap::getInstance();
		if(
			($baseColorTag = $nbt->getTag(self::TAG_BASE)) instanceof IntTag &&
			($baseColor = $colorIdMap->fromInvertedId($baseColorTag->getValue())) !== null
		){
			$this->baseColor = $baseColor;
		}else{
			$this->baseColor = DyeColor::BLACK; //TODO: this should be an error
		}

		$patternTypeIdMap = BannerPatternTypeIdMap::getInstance();

		$patterns = $nbt->getListTag(self::TAG_PATTERNS);
		if($patterns !== null){
			/** @var CompoundTag $pattern */
			foreach($patterns as $pattern){
				$patternColor = $colorIdMap->fromInvertedId($pattern->getInt(self::TAG_PATTERN_COLOR)) ?? DyeColor::BLACK; //TODO: missing pattern colour should be an error
				$patternType = $patternTypeIdMap->fromId($pattern->getString(self::TAG_PATTERN_NAME));
				if($patternType === null){
					continue; //TODO: this should be an error, but right now we don't have the setup to deal with it
				}
				$this->patterns[] = new BannerPatternLayer($patternType, $patternColor);
			}
		}
	}

	protected function writeSaveData(CompoundTag $nbt) : void{
		$colorIdMap = DyeColorIdMap::getInstance();
		$patternIdMap = BannerPatternTypeIdMap::getInstance();
		$nbt->setInt(self::TAG_BASE, $colorIdMap->toInvertedId($this->baseColor));
		$patterns = new ListTag();
		foreach($this->patterns as $pattern){
			$patterns->push(CompoundTag::create()
				->setString(self::TAG_PATTERN_NAME, $patternIdMap->toId($pattern->getType()))
				->setInt(self::TAG_PATTERN_COLOR, $colorIdMap->toInvertedId($pattern->getColor()))
			);
		}
		$nbt->setTag(self::TAG_PATTERNS, $patterns);
	}

	protected function addAdditionalSpawnData(CompoundTag $nbt) : void{
		$colorIdMap = DyeColorIdMap::getInstance();
		$patternIdMap = BannerPatternTypeIdMap::getInstance();
		$nbt->setInt(self::TAG_BASE, $colorIdMap->toInvertedId($this->baseColor));
		$patterns = new ListTag();
		foreach($this->patterns as $pattern){
			$patterns->push(CompoundTag::create()
				->setString(self::TAG_PATTERN_NAME, $patternIdMap->toId($pattern->getType()))
				->setInt(self::TAG_PATTERN_COLOR, $colorIdMap->toInvertedId($pattern->getColor()))
			);
		}
		$nbt->setTag(self::TAG_PATTERNS, $patterns);
	}

	/**
	 * Returns the color of the banner base.
	 */
	public function getBaseColor() : DyeColor{
		return $this->baseColor;
	}

	/**
	 * Sets the color of the banner base.
	 */
	public function setBaseColor(DyeColor $color) : void{
		$this->baseColor = $color;
	}

	/**
	 * @return BannerPatternLayer[]
	 * @phpstan-return list<BannerPatternLayer>
	 */
	public function getPatterns() : array{
		return $this->patterns;
	}

	/**
	 * @param BannerPatternLayer[] $patterns
	 *
	 * @phpstan-param list<BannerPatternLayer> $patterns
	 */
	public function setPatterns(array $patterns) : void{
		$this->patterns = $patterns;
	}

	public function getDefaultName() : string{
		return "Banner";
	}
}