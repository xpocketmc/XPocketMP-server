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
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

/**
 * This class provides a complete implementation of a regular inventory.
 */
class SimpleInventory extends BaseInventory{
	/**
	 * @var \SplFixedArray|(Item|null)[]
	 * @phpstan-var \SplFixedArray<Item|null>
	 */
	protected \SplFixedArray $slots;

	public function __construct(int $size){
		$this->slots = new \SplFixedArray($size);
		parent::__construct();
	}

	/**
	 * Returns the size of the inventory.
	 */
	public function getSize() : int{
		return $this->slots->getSize();
	}

	public function getItem(int $index) : Item{
		return $this->slots[$index] !== null ? clone $this->slots[$index] : VanillaItems::AIR();
	}

	protected function internalSetItem(int $index, Item $item) : void{
		$this->slots[$index] = $item->isNull() ? null : $item;
	}

	/**
	 * @return Item[]
	 * @phpstan-return array<int, Item>
	 */
	public function getContents(bool $includeEmpty = false) : array{
		$contents = [];

		foreach($this->slots as $i => $slot){
			if($slot !== null){
				$contents[$i] = clone $slot;
			}elseif($includeEmpty){
				$contents[$i] = VanillaItems::AIR();
			}
		}

		return $contents;
	}

	protected function internalSetContents(array $items) : void{
		for($i = 0, $size = $this->getSize(); $i < $size; ++$i){
			if(!isset($items[$i]) || $items[$i]->isNull()){
				$this->slots[$i] = null;
			}else{
				$this->slots[$i] = clone $items[$i];
			}
		}
	}

	protected function getMatchingItemCount(int $slot, Item $test, bool $checkTags) : int{
		$slotItem = $this->slots[$slot];
		return $slotItem !== null && $slotItem->equals($test, true, $checkTags) ? $slotItem->getCount() : 0;
	}

	public function isSlotEmpty(int $index) : bool{
		return $this->slots[$index] === null || $this->slots[$index]->isNull();
	}
}
