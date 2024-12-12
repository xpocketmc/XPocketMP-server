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

namespace pocketmine\inventory\transaction\action;

use pocketmine\inventory\transaction\TransactionValidationException;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

/**
 * This action is used by creative players to balance transactions involving the creative inventory menu.
 * The source item is the item being created ("taken" from the creative menu).
 */
class CreateItemAction extends InventoryAction{

	public function __construct(Item $sourceItem){
		parent::__construct($sourceItem, VanillaItems::AIR());
	}

	public function validate(Player $source) : void{
		if($source->hasFiniteResources()){
			throw new TransactionValidationException("Player has finite resources, cannot create items");
		}
		if(!$source->getCreativeInventory()->contains($this->sourceItem)){
			throw new TransactionValidationException("Creative inventory does not contain requested item");
		}
	}

	public function execute(Player $source) : void{
		//NOOP
	}
}
