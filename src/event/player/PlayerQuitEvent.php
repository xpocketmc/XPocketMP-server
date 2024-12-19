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

use pocketmine\lang\Translatable;
use pocketmine\player\Player;

/**
 * Called when a player disconnects from the server for any reason.
 *
 * Some possible reasons include:
 * - being kicked by an operator
 * - disconnecting from the game
 * - timeout due to network connectivity issues
 *
 * @see PlayerKickEvent
 */
class PlayerQuitEvent extends PlayerEvent{
	public function __construct(
		Player $player,
		protected Translatable|string $quitMessage,
		protected Translatable|string $quitReason
	){
		$this->player = $player;
	}

	/**
	 * Sets the quit message broadcasted to other players.
	 */
	public function setQuitMessage(Translatable|string $quitMessage) : void{
		$this->quitMessage = $quitMessage;
	}

	/**
	 * Returns the quit message broadcasted to other players, e.g. "Steve left the game".
	 */
	public function getQuitMessage() : Translatable|string{
		return $this->quitMessage;
	}

	/**
	 * Returns the disconnect reason shown in the server log and on the console.
	 */
	public function getQuitReason() : Translatable|string{
		return $this->quitReason;
	}
}
