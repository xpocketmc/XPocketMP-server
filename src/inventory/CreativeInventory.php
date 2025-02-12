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
use pocketmine\inventory\data\CreativeGroupedItem;
use pocketmine\inventory\data\CreativeItemGroup;
use pocketmine\inventory\json\CreativeGroupData;
use pocketmine\inventory\json\CreativeItemData;
use pocketmine\item\Item;
use pocketmine\utils\DestructorCallbackTrait;
use pocketmine\utils\Filesystem;
use pocketmine\utils\ObjectSet;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\Utils;
use function array_map;
use function count;
use function json_decode;

final class CreativeInventory{
	use SingletonTrait;
	use DestructorCallbackTrait;

	/**
	 * @var CreativeItemGroup[]
	 * @phpstan-var array<int, CreativeItemGroup>
	 */
	private array $groups = [];

	/**
	 * @var CreativeGroupedItem[]
	 * @phpstan-var array<int, CreativeGroupedItem>
	 */
	private array $items = [];

	/** @phpstan-var ObjectSet<\Closure() : void> */
	private ObjectSet $contentChangedCallbacks;

	private function __construct(){
		$this->contentChangedCallbacks = new ObjectSet();

		$creativeData = json_decode(Filesystem::fileGetContents(BedrockDataFiles::CREATIVEITEMS_JSON));

		$mapper = new \JsonMapper();
		$mapper->bStrictObjectTypeChecking = true;
		$mapper->bExceptionOnUndefinedProperty = true;
		$mapper->bExceptionOnMissingData = true;

		$groups = CraftingManagerFromDataHelper::loadJsonObjectListIntoModel($mapper, CreativeGroupData::class, $creativeData->groups);
		$items = CraftingManagerFromDataHelper::loadJsonObjectListIntoModel($mapper, CreativeItemData::class, $creativeData->items);

		foreach($groups as $data){
			$icon = $data->icon === null ? null : CraftingManagerFromDataHelper::deserializeItemStack($data->icon); //todo: discuss what we do if the icon is unknown
			$this->addGroup($data->category_id, $data->category_name, $icon);
		}

		foreach($items as $data){
			$item = CraftingManagerFromDataHelper::deserializeItemStack($data->item);
			if($item === null){
				//unknown item
				continue;
			}
			$this->add($item, $data->group_id);
		}
	}

	/**
	 * Removes all previously added items from the creative menu.
	 * Note: Players who are already online when this is called will not see this change.
	 */
	public function clear() : void{
		$this->groups = [];
		$this->items = [];
		$this->onContentChange();
	}

	/**
	 * @return Item[]
	 * @phpstan-return array<int, Item>
	 */
	public function getAll() : array{
		return Utils::cloneObjectArray(array_map(static fn(CreativeGroupedItem $d) => $d->item, $this->items));
	}

	/**
	 * @return CreativeGroupedItem[]
	 * @phpstan-return array<int, CreativeGroupedItem>
	 */
	public function getGroupedItems() : array{
		return Utils::cloneObjectArray($this->items);
	}

	/**
	 * @return CreativeItemGroup[]
	 * @phpstan-return array<int, CreativeItemGroup>
	 */
	public function getGroups() : array{
		return Utils::cloneObjectArray($this->groups);
	}

	public function getItem(int $index) : ?Item{
		return isset($this->items[$index]) ? clone $this->items[$index]->item : null;
	}

	public function getItemIndex(Item $item) : int{
		foreach($this->items as $i => $d){
			if($item->equals($d->item, true, false)){
				return $i;
			}
		}

		return -1;
	}

	/**
	 * Adds an item to the creative menu.
	 * Note: Players who are already online when this is called will not see this change.
	 */
	public function add(Item $item, int $groupId) : void{
		$groupedItem = new CreativeGroupedItem();

		$groupedItem->item = clone $item;
		$groupedItem->groupId = $groupId;

		$this->items[] = $groupedItem;
		$this->onContentChange();
	}

	/**
	 * Adds a group to the creative menu.
	 * Note: Players who are already online when this is called will not see this change.
	 */
	public function addGroup(int $categoryId, string $categoryName, ?Item $icon = null) : int{
		$group = new CreativeItemGroup();

		$group->categoryId = $categoryId;
		$group->categoryName = $categoryName;
		$group->icon = $icon;

		$this->groups[] = $group;
		$this->onContentChange();

		return count($this->groups) - 1;
	}

	/**
	 * Removes an item from the creative menu.
	 * Note: Players who are already online when this is called will not see this change.
	 */
	public function remove(Item $item) : void{
		$index = $this->getItemIndex($item);
		if($index !== -1){
			unset($this->items[$index]);
			$this->onContentChange();
		}
	}

	/**
	 * Removes a group from the creative menu.
	 * Note: Players who are already online when this is called will not see this change.
	 */
	public function removeGroup(int $groupId) : void{
		foreach($this->items as $d){
			if($d->groupId === $groupId){
				throw new \InvalidArgumentException("Cannot remove group with items in it");
			}
		}

		foreach($this->items as $i => $d){
			if($d->groupId === $groupId){
				unset($this->items[$i]);
			}
		}
		$this->onContentChange();
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
