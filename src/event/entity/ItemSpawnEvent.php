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

namespace pocketmine\event\entity;

use pocketmine\entity\object\ItemEntity;

/**
 * Called when an item is spawned or loaded.
 *
 * Some possible reasons include:
 * - item is loaded from disk
 * - player dropping an item
 * - block drops
 * - loot of a player or entity
 *
 * @see PlayerDropItemEvent
 * @phpstan-extends EntityEvent<ItemEntity>
 */
class ItemSpawnEvent extends EntityEvent{

	public function __construct(ItemEntity $item){
		$this->entity = $item;

	}

	/**
	 * @return ItemEntity
	 */
	public function getEntity(){
		return $this->entity;
	}
}
