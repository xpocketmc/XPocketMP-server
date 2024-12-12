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
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\item\Item;
use pocketmine\player\Player;

/**
 * Called when a player middle-clicks on a block to get an item in creative mode.
 */
class PlayerBlockPickEvent extends PlayerEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		Player $player,
		private Block $blockClicked,
		private Item $resultItem
	){
		$this->player = $player;
	}

	public function getBlock() : Block{
		return $this->blockClicked;
	}

	public function getResultItem() : Item{
		return $this->resultItem;
	}
}
