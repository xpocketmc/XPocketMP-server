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

use pocketmine\player\Player;

/**
 * Called when a player requests a different viewing distance than the current one.
 */
class PlayerViewDistanceChangeEvent extends PlayerEvent{
	public function __construct(
		Player $player,
		protected int $oldDistance,
		protected int $newDistance
	){
		$this->player = $player;
	}

	/**
	 * Returns the new view radius, measured in chunks.
	 */
	public function getNewDistance() : int{
		return $this->newDistance;
	}

	/**
	 * Returns the old view radius, measured in chunks.
	 * A value of -1 means that the player has just connected and did not have a view distance before this event.
	 */
	public function getOldDistance() : int{
		return $this->oldDistance;
	}
}
