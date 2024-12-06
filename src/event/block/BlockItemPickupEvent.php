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

namespace XPocketMP\event\block;

use XPocketMPlock\Block;
use XPocketMP\entity\Entity;
use XPocketMP\event\Cancellable;
use XPocketMP\event\CancellableTrait;
use XPocketMP\inventory\Inventory;
use XPocketMP\item\Item;

/**
 * Called when a block picks up an item, arrow, etc.
 */
class BlockItemPickupEvent extends BlockEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		Block $collector,
		private Entity $origin,
		private Item $item,
		private ?Inventory $inventory
	){
		parent::__construct($collector);
	}

	public function getOrigin() : Entity{
		return $this->origin;
	}

	/**
	 * Items to be received
	 */
	public function getItem() : Item{
		return clone $this->item;
	}

	/**
	 * Change the items to receive.
	 */
	public function setItem(Item $item) : void{
		$this->item = clone $item;
	}

	/**
	 * Inventory to which received items will be added.
	 */
	public function getInventory() : ?Inventory{
		return $this->inventory;
	}

	/**
	 * Change the inventory to which received items are added.
	 */
	public function setInventory(?Inventory $inventory) : void{
		$this->inventory = $inventory;
	}
}