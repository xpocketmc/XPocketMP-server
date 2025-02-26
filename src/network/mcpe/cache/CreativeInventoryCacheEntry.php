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

namespace pocketmine\network\mcpe\cache;

use pocketmine\inventory\CreativeCategory;
use pocketmine\inventory\CreativeGroup;
use pocketmine\network\mcpe\protocol\types\inventory\CreativeItemEntry;

final class CreativeInventoryCacheEntry{

	/**
	 * @param CreativeCategory[]     $categories
	 * @param CreativeGroup[]|null[] $groups
	 * @param CreativeItemEntry[]    $items
	 *
	 * @phpstan-param list<CreativeCategory>   $categories
	 * @phpstan-param list<CreativeGroup|null> $groups
	 * @phpstan-param list<CreativeItemEntry>  $items
	 */
	public function __construct(
		public readonly array $categories,
		public readonly array $groups,
		public readonly array $items,
	){
		//NOOP
	}
}
