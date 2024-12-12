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

namespace pocketmine\event\player;

use pocketmine\lang\Translatable;

trait PlayerDisconnectEventTrait{
	/**
	 * Sets the kick reason shown in the server log and on the console.
	 *
	 * This should be a **short, simple, single-line** message.
	 * Do not use long or multi-line messages here - they will spam the log and server console with useless information.
	 */
	public function setDisconnectReason(Translatable|string $disconnectReason) : void{
		$this->disconnectReason = $disconnectReason;
	}

	/**
	 * Returns the kick reason shown in the server log and on the console.
	 * When kicked by the /kick command, the default is something like "Kicked by admin.".
	 */
	public function getDisconnectReason() : Translatable|string{
		return $this->disconnectReason;
	}

	/**
	 * Sets the message shown on the player's disconnection screen.
	 * This can be as long as you like, and may contain formatting and newlines.
	 * If this is set to null, the kick reason will be used as the disconnect screen message directly.
	 */
	public function setDisconnectScreenMessage(Translatable|string|null $disconnectScreenMessage) : void{
		$this->disconnectScreenMessage = $disconnectScreenMessage;
	}

	/**
	 * Returns the message shown on the player's disconnection screen.
	 * When kicked by the /kick command, the default is something like "Kicked by admin.".
	 * If this is null, the kick reason will be used as the disconnect screen message directly.
	 */
	public function getDisconnectScreenMessage() : Translatable|string|null{ return $this->disconnectScreenMessage ?? $this->disconnectReason; }
}
