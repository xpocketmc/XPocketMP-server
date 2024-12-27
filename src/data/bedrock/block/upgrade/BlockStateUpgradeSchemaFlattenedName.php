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

use function ksort;
use const SORT_STRING;

final class BlockStateUpgradeSchemaFlattenedName{

	/**
	 * @param string[] $flattenedValueRemaps
	 * @phpstan-param array<string, string> $flattenedValueRemaps
	 */
	public function __construct(
		public string $prefix,
		public string $flattenedProperty,
		public string $suffix,
		public array $flattenedValueRemaps
	){
		ksort($this->flattenedValueRemaps, SORT_STRING);
	}

	public function equals(self $that) : bool{
		return $this->prefix === $that->prefix &&
			$this->flattenedProperty === $that->flattenedProperty &&
			$this->suffix === $that->suffix &&
			$this->flattenedValueRemaps === $that->flattenedValueRemaps;
	}
}
