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
 * Developed: ClousClouds, PMMP Team
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\data\bedrock\block\upgrade\model;

use function count;

final class BlockStateUpgradeSchemaModelFlattenedName implements \JsonSerializable{

	/** @required */
	public string $prefix;
	/** @required */
	public string $flattenedProperty;
	/** @required */
	public string $suffix;
	/**
	 * @var string[]
	 * @phpstan-var array<string, string>
	 */
	public array $flattenedValueRemaps;

	/**
	 * @param string[] $flattenedValueRemaps
	 * @phpstan-param array<string, string> $flattenedValueRemaps
	 */
	public function __construct(string $prefix, string $flattenedProperty, string $suffix, array $flattenedValueRemaps){
		$this->prefix = $prefix;
		$this->flattenedProperty = $flattenedProperty;
		$this->suffix = $suffix;
		$this->flattenedValueRemaps = $flattenedValueRemaps;
	}

	/**
	 * @return mixed[]
	 */
	public function jsonSerialize() : array{
		$result = (array) $this;
		if(count($this->flattenedValueRemaps) === 0){
			unset($result["flattenedValueRemaps"]);
		}
		return $result;
	}
}
