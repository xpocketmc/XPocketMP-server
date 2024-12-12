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
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\object\ItemEntity;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

/**
 * Called when a dropped item tries to despawn due to its despawn delay running out.
 * Cancelling the event will reset the despawn delay to default (5 minutes).
 *
 * @phpstan-extends EntityEvent<ItemEntity>
 */
class ItemDespawnEvent extends EntityEvent implements Cancellable{
	use CancellableTrait;

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
