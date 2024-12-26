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

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

/**
 * Called when a player's data is about to be saved to disk.
 */
class PlayerDataSaveEvent extends Event implements Cancellable{
	use CancellableTrait;

	public function __construct(
		protected CompoundTag $data,
		protected string $playerName,
		private ?Player $player
	){}

	/**
	 * Returns the data to be written to disk as a CompoundTag
	 */
	public function getSaveData() : CompoundTag{
		return $this->data;
	}

	public function setSaveData(CompoundTag $data) : void{
		$this->data = $data;
	}

	/**
	 * Returns the username of the player whose data is being saved. This is not necessarily an online player.
	 */
	public function getPlayerName() : string{
		return $this->playerName;
	}

	/**
	 * Returns the player whose data is being saved, if online.
	 * If null, this data is for an offline player (possibly just disconnected).
	 */
	public function getPlayer() : ?Player{
		return $this->player;
	}
}
