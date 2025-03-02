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

use pocketmine\crafting\CraftingManagerFromDataHelper;
use pocketmine\data\bedrock\BedrockDataFiles;
use pocketmine\inventory\json\CreativeGroupData;
use pocketmine\item\Item;
use pocketmine\lang\Translatable;
use pocketmine\utils\DestructorCallbackTrait;
use pocketmine\utils\ObjectSet;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;
use function array_filter;
use function array_map;

final class CreativeInventory{
	use SingletonTrait;
	use DestructorCallbackTrait;

	/**
	 * @var CreativeInventoryEntry[]
	 * @phpstan-var array<int, CreativeInventoryEntry>
	 */
	private array $creative = [];

	/** @phpstan-var ObjectSet<\Closure() : void> */
	private ObjectSet $contentChangedCallbacks;

	private function __construct(){
		$this->contentChangedCallbacks = new ObjectSet();

		foreach([
			"construction" => CreativeCategory::CONSTRUCTION,
			"nature" => CreativeCategory::NATURE,
			"equipment" => CreativeCategory::EQUIPMENT,
			"items" => CreativeCategory::ITEMS,
		] as $categoryId => $categoryEnum){
			$groups = CraftingManagerFromDataHelper::loadJsonArrayOfObjectsFile(
				Path::join(BedrockDataFiles::CREATIVE, $categoryId . ".json"),
				CreativeGroupData::class
			);

			foreach($groups as $groupData){
				$icon = $groupData->group_icon === null ? null : CraftingManagerFromDataHelper::deserializeItemStack($groupData->group_icon);

				$group = $icon === null ? null : new CreativeGroup(
					new Translatable($groupData->group_name),
					$icon
				);

				$items = array_filter(array_map(static fn($itemStack) => CraftingManagerFromDataHelper::deserializeItemStack($itemStack), $groupData->items));

				foreach($items as $item){
					$this->add($item, $categoryEnum, $group);
				}
			}
		}
	}

	/**
	 * Removes all previously added items from the creative menu.
	 * Note: Players who are already online when this is called will not see this change.
	 */
	public function clear() : void{
		$this->creative = [];
		$this->onContentChange();
	}

	/**
	 * @return Item[]
	 * @phpstan-return array<int, Item>
	 */
	public function getAll() : array{
		return array_map(fn(CreativeInventoryEntry $entry) => $entry->getItem(), $this->creative);
	}

	/**
	 * @return CreativeInventoryEntry[]
	 * @phpstan-return array<int, CreativeInventoryEntry>
	 */
	public function getAllEntries() : array{
		return $this->creative;
	}

	public function getItem(int $index) : ?Item{
		return $this->getEntry($index)?->getItem();
	}

	public function getEntry(int $index) : ?CreativeInventoryEntry{
		return $this->creative[$index] ?? null;
	}

	public function getItemIndex(Item $item) : int{
		foreach($this->creative as $i => $d){
			if($d->matchesItem($item)){
				return $i;
			}
		}

		return -1;
	}

	/**
	 * Adds an item to the creative menu.
	 * Note: Players who are already online when this is called will not see this change.
	 */
	public function add(Item $item, CreativeCategory $category = CreativeCategory::ITEMS, ?CreativeGroup $group = null) : void{
		$this->creative[] = new CreativeInventoryEntry($item, $category, $group);
		$this->onContentChange();
	}

	/**
	 * Removes an item from the creative menu.
	 * Note: Players who are already online when this is called will not see this change.
	 */
	public function remove(Item $item) : void{
		$index = $this->getItemIndex($item);
		if($index !== -1){
			unset($this->creative[$index]);
			$this->onContentChange();
		}
	}

	public function contains(Item $item) : bool{
		return $this->getItemIndex($item) !== -1;
	}

	/** @phpstan-return ObjectSet<\Closure() : void> */
	public function getContentChangedCallbacks() : ObjectSet{
		return $this->contentChangedCallbacks;
	}

	private function onContentChange() : void{
		foreach($this->contentChangedCallbacks as $callback){
			$callback();
		}
	}
}
