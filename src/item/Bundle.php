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

namespace pocketmine\item;

use pocketmine\inventory\InventoryHolder;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use function ceil;

class Bundle extends Item implements InventoryHolder{
	private const MAX_CAPACITY = 64;
	private array $contents = [];
	private int $currentCapacity = 0;

	public function __construct(){
		parent::__construct(ItemTypeIds::BUNDLE, 0, "Bundle");
	}

	public function addItem(Item $item) : string{
		$itemSize = $this->getItemSize($item);
		if($this->currentCapacity + $itemSize > self::MAX_CAPACITY){
			return "The Bundle is full!";
		}
		$this->contents[] = clone $item;
		$this->currentCapacity += $itemSize;
		return $item->getName() . " added to the Bundle!";
	}

	public function getContents() : array{
		return $this->contents;
	}

	public function clearContents() : void{
		$this->contents = [];
		$this->currentCapacity = 0;
	}

	private function getItemSize(Item $item) : int{
		if($item->getMaxStackSize() === 1){
			return self::MAX_CAPACITY;
		}
		return (int) ceil(self::MAX_CAPACITY / $item->getMaxStackSize());
	}

	public function displayContents() : string{
		if(empty($this->contents)){
			return "The Bundle is empty.";
		}
		$message = "Bundle contains:";
		foreach($this->contents as $item){
			$message .= "\n- " . $item->getName() . " x " . $item->getCount();
		}
		return $message;
	}

	public function writeSaveData(CompoundTag $nbt) : void{
		parent::writeSaveData($nbt);
		$items = [];
		foreach($this->contents as $item){
			$items[] = $item->nbtSerialize();
		}
		$nbt->setTag("BundleItems", CompoundTag::createFromArray($items));
		$nbt->setInt("CurrentCapacity", $this->currentCapacity);
	}

	public function readSaveData(CompoundTag $nbt) : void{
		parent::readSaveData($nbt);
		if($nbt->hasTag("BundleItems")){
			foreach($nbt->getListTag("BundleItems")->getValue() as $itemTag){
				$this->contents[] = Item::nbtDeserialize($itemTag);
			}
		}
		$this->currentCapacity = $nbt->getInt("CurrentCapacity", 0);
	}

	public function onClickAir(Player $player, Vector3 $directionVector) : bool{
		$player->sendMessage(TextFormat::YELLOW . $this->displayContents());
		return true;
	}

	public function useOn(Item $usedWith, Player $player) : bool{
		$message = $this->addItem($usedWith);
		$player->getInventory()->removeItem($usedWith);
		$player->sendMessage(TextFormat::YELLOW . $message);
		return true;
	}
}
