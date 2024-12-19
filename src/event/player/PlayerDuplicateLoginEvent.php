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
use pocketmine\event\Event;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\NetworkSession;

/**
 * Called when a player connects with a username or UUID that is already used by another player on the server.
 * If cancelled, the newly connecting session will be disconnected; otherwise, the existing player will be disconnected.
 */
class PlayerDuplicateLoginEvent extends Event implements Cancellable{
	use CancellableTrait;
	use PlayerDisconnectEventTrait;

	public function __construct(
		private NetworkSession $connectingSession,
		private NetworkSession $existingSession,
		private Translatable|string $disconnectReason,
		private Translatable|string|null $disconnectScreenMessage
	){}

	public function getConnectingSession() : NetworkSession{
		return $this->connectingSession;
	}

	public function getExistingSession() : NetworkSession{
		return $this->existingSession;
	}
}
