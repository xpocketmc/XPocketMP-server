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

namespace XPocketMP\event\player;

use XPocketMP\event\Cancellable;
use XPocketMP\event\CancellableTrait;
use XPocketMP\inventory\transaction\EnchantingTransaction;
use XPocketMP\item\enchantment\EnchantingOption;
use XPocketMP\item\Item;
use XPocketMP\player\Player;

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