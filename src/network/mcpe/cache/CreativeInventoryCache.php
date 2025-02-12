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

use pocketmine\inventory\CreativeInventory;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\CreativeContentPacket;
use pocketmine\network\mcpe\protocol\types\inventory\CreativeGroupEntry;
use pocketmine\network\mcpe\protocol\types\inventory\CreativeItemEntry;
use pocketmine\item\VanillaItems;
use pocketmine\utils\SingletonTrait;
use function spl_object_id;

final class CreativeInventoryCache {
	use SingletonTrait;

	/**
	 * @var CreativeContentPacket[]
	 * @phpstan-var array<int, CreativeContentPacket>
	 */
	private array $caches = [];

	public function getCache(CreativeInventory $inventory) : CreativeContentPacket {
		$id = spl_object_id($inventory);
		if (!isset($this->caches[$id])) {
			$inventory->getDestructorCallbacks()->add(function() use ($id) : void {
				unset($this->caches[$id]);
			});
			$inventory->getContentChangedCallbacks()->add(function() use ($id) : void {
				unset($this->caches[$id]);
			});
			$this->caches[$id] = $this->buildCreativeInventoryCache($inventory);
		}
		return $this->caches[$id];
	}

	/**
	 * Rebuild the cache for the given inventory.
	 */
	private function buildCreativeInventoryCache(CreativeInventory $inventory) : CreativeContentPacket {
		$groups = [];
		$items = [];
		$typeConverter = TypeConverter::getInstance();
		$icon = VanillaItems::getInstance()->get(1);
		$groups[] = new CreativeGroupEntry(0, "Default Group", $typeConverter->coreItemStackToNet($icon));

		//creative inventory may have holes if items were unregistered - ensure network IDs used are always consistent
		foreach ($inventory->getAll() as $k => $item) {
			$items[] = new CreativeItemEntry($k, $typeConverter->coreItemStackToNet($item));
		}

		return CreativeContentPacket::create($groups, $items);
	}
}