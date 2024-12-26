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

namespace pocketmine\network\mcpe;

use pocketmine\inventory\Inventory;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;

final class InventoryManagerEntry{
	/**
	 * @var ItemStack[]
	 * @phpstan-var array<int, ItemStack>
	 */
	public array $predictions = [];

	/**
	 * @var ItemStackInfo[]
	 * @phpstan-var array<int, ItemStackInfo>
	 */
	public array $itemStackInfos = [];

	/**
	 * @var ItemStack[]
	 * @phpstan-var array<int, ItemStack>
	 */
	public array $pendingSyncs = [];

	public function __construct(
		public Inventory $inventory,
		public ?ComplexInventoryMapEntry $complexSlotMap = null
	){}
}
