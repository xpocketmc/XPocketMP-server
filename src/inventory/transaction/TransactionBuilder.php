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

namespace pocketmine\inventory\transaction;

use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\action\InventoryAction;
use function spl_object_id;

final class TransactionBuilder{

	/** @var TransactionBuilderInventory[] */
	private array $inventories = [];

	/** @var InventoryAction[] */
	private array $extraActions = [];

	public function addAction(InventoryAction $action) : void{
		$this->extraActions[spl_object_id($action)] = $action;
	}

	public function getInventory(Inventory $inventory) : TransactionBuilderInventory{
		$id = spl_object_id($inventory);
		return $this->inventories[$id] ??= new TransactionBuilderInventory($inventory);
	}

	/**
	 * @return InventoryAction[]
	 */
	public function generateActions() : array{
		$actions = $this->extraActions;

		foreach($this->inventories as $inventory){
			foreach($inventory->generateActions() as $action){
				$actions[spl_object_id($action)] = $action;
			}
		}

		return $actions;
	}
}
