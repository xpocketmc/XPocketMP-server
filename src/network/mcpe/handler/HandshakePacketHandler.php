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

namespace pocketmine\network\mcpe\handler;

use pocketmine\network\mcpe\protocol\ClientToServerHandshakePacket;

/**
 * Handler responsible for awaiting client response from crypto handshake.
 */
class HandshakePacketHandler extends PacketHandler{
	/**
	 * @phpstan-param \Closure() : void $onHandshakeCompleted
	 */
	public function __construct(private \Closure $onHandshakeCompleted){}

	public function handleClientToServerHandshake(ClientToServerHandshakePacket $packet) : bool{
		($this->onHandshakeCompleted)();
		return true;
	}
}
