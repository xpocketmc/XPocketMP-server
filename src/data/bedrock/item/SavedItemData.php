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

namespace pocketmine\data\bedrock\item;

use pocketmine\data\bedrock\block\BlockStateData;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\VersionInfo;

final class SavedItemData{

	public const TAG_NAME = "Name";
	public const TAG_DAMAGE = "Damage";
	public const TAG_BLOCK = "Block";
	public const TAG_TAG = "tag";

	public function __construct(
		private string $name,
		private int $meta = 0,
		private ?BlockStateData $block = null,
		private ?CompoundTag $tag = null
	){}

	public function getName() : string{ return $this->name; }

	public function getMeta() : int{ return $this->meta; }

	public function getBlock() : ?BlockStateData{ return $this->block; }

	public function getTag() : ?CompoundTag{ return $this->tag; }

	public function toNbt() : CompoundTag{
		$result = CompoundTag::create();
		$result->setString(self::TAG_NAME, $this->name);
		$result->setShort(self::TAG_DAMAGE, $this->meta);

		if($this->block !== null){
			$result->setTag(self::TAG_BLOCK, $this->block->toNbt());
		}
		if($this->tag !== null){
			$result->setTag(self::TAG_TAG, $this->tag);
		}
		$result->setLong(VersionInfo::TAG_WORLD_DATA_VERSION, VersionInfo::WORLD_DATA_VERSION);

		return $result;
	}
}
