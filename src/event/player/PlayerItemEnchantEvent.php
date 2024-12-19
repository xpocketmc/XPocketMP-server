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

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\inventory\transaction\EnchantingTransaction;
use pocketmine\item\enchantment\EnchantingOption;
use pocketmine\item\Item;
use pocketmine\player\Player;

/**
 * Called when a player enchants an item using an enchanting table.
 */
class PlayerItemEnchantEvent extends PlayerEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		Player $player,
		private readonly EnchantingTransaction $transaction,
		private readonly EnchantingOption $option,
		private readonly Item $inputItem,
		private readonly Item $outputItem,
		private readonly int $cost
	){
		$this->player = $player;
	}

	/**
	 * Returns the inventory transaction involved in this enchant event.
	 */
	public function getTransaction() : EnchantingTransaction{
		return $this->transaction;
	}

	/**
	 * Returns the enchantment option used.
	 */
	public function getOption() : EnchantingOption{
		return $this->option;
	}

	/**
	 * Returns the item to be enchanted.
	 */
	public function getInputItem() : Item{
		return clone $this->inputItem;
	}

	/**
	 * Returns the enchanted item.
	 */
	public function getOutputItem() : Item{
		return clone $this->outputItem;
	}

	/**
	 * Returns the number of XP levels and lapis that will be subtracted after enchanting
	 * if the player is not in creative mode.
	 */
	public function getCost() : int{
		return $this->cost;
	}
}
