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

namespace pocketmine\data\bedrock\block\upgrade;

use pocketmine\data\bedrock\block\upgrade\BlockStateUpgradeSchemaFlattenInfo as FlattenInfo;
use pocketmine\data\bedrock\block\upgrade\BlockStateUpgradeSchemaValueRemap as ValueRemap;
use pocketmine\nbt\tag\Tag;
use function count;

final class BlockStateUpgradeSchema{
	/**
	 * @var string[]
	 * @phpstan-var array<string, string>
	 */
	public array $renamedIds = [];

	/**
	 * @var Tag[][]
	 * @phpstan-var array<string, array<string, Tag>>
	 */
	public array $addedProperties = [];

	/**
	 * @var string[][]
	 * @phpstan-var array<string, list<string>>
	 */
	public array $removedProperties = [];

	/**
	 * @var string[][]
	 * @phpstan-var array<string, array<string, string>>
	 */
	public array $renamedProperties = [];

	/**
	 * @var ValueRemap[][][]
	 * @phpstan-var array<string, array<string, list<ValueRemap>>>
	 */
	public array $remappedPropertyValues = [];

	/**
	 * @var FlattenInfo[]
	 * @phpstan-var array<string, FlattenInfo>
	 */
	public array $flattenedProperties = [];

	/**
	 * @var BlockStateUpgradeSchemaBlockRemap[][]
	 * @phpstan-var array<string, list<BlockStateUpgradeSchemaBlockRemap>>
	 */
	public array $remappedStates = [];

	public readonly int $versionId;

	public function __construct(
		public readonly int $maxVersionMajor,
		public readonly int $maxVersionMinor,
		public readonly int $maxVersionPatch,
		public readonly int $maxVersionRevision,
		private int $schemaId
	){
		$this->versionId = ($this->maxVersionMajor << 24) | ($this->maxVersionMinor << 16) | ($this->maxVersionPatch << 8) | $this->maxVersionRevision;
	}

	/**
	 * @deprecated This is defined by Mojang, and therefore cannot be relied on. Use getSchemaId() instead for
	 * internal version management.
	 */
	public function getVersionId() : int{
		return $this->versionId;
	}

	public function getSchemaId() : int{ return $this->schemaId; }

	public function isEmpty() : bool{
		foreach([
			$this->renamedIds,
			$this->addedProperties,
			$this->removedProperties,
			$this->renamedProperties,
			$this->remappedPropertyValues,
			$this->flattenedProperties,
			$this->remappedStates,
		] as $list){
			if(count($list) !== 0){
				return false;
			}
		}

		return true;
	}
}
