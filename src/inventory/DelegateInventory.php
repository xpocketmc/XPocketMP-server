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

namespace pocketmine\inventory;

use pocketmine\item\Item;

/**
 * An inventory which is backed by another inventory, and acts as a proxy to that inventory.
 */
class DelegateInventory extends BaseInventory{
	private InventoryListener $inventoryListener;
	private bool $backingInventoryChanging = false;

	public function __construct(
		private Inventory $backingInventory
	){
		parent::__construct();
		$weakThis = \WeakReference::create($this);
		$this->backingInventory->getListeners()->add($this->inventoryListener = new CallbackInventoryListener(
			static function(Inventory $unused, int $slot, Item $oldItem) use ($weakThis) : void{
				if(($strongThis = $weakThis->get()) !== null){
					$strongThis->backingInventoryChanging = true;
					try{
						$strongThis->onSlotChange($slot, $oldItem);
					}finally{
						$strongThis->backingInventoryChanging = false;
					}
				}
			},
			static function(Inventory $unused, array $oldContents) use ($weakThis) : void{
				if(($strongThis = $weakThis->get()) !== null){
					$strongThis->backingInventoryChanging = true;
					try{
						$strongThis->onContentChange($oldContents);
					}finally{
						$strongThis->backingInventoryChanging = false;
					}
				}
			}
		));
	}

	public function __destruct(){
		$this->backingInventory->getListeners()->remove($this->inventoryListener);
	}

	public function getSize() : int{
		return $this->backingInventory->getSize();
	}

	public function getItem(int $index) : Item{
		return $this->backingInventory->getItem($index);
	}

	protected function internalSetItem(int $index, Item $item) : void{
		$this->backingInventory->setItem($index, $item);
	}

	public function getContents(bool $includeEmpty = false) : array{
		return $this->backingInventory->getContents($includeEmpty);
	}

	protected function internalSetContents(array $items) : void{
		$this->backingInventory->setContents($items);
	}

	public function isSlotEmpty(int $index) : bool{
		return $this->backingInventory->isSlotEmpty($index);
	}

	protected function onSlotChange(int $index, Item $before) : void{
		if($this->backingInventoryChanging){
			parent::onSlotChange($index, $before);
		}
	}

	protected function onContentChange(array $itemsBefore) : void{
		if($this->backingInventoryChanging){
			parent::onContentChange($itemsBefore);
		}
	}
}
