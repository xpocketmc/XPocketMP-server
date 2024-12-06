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

namespace XPocketMP\network\mcpe\handler;

use XPocketMP\network\mcpe\protocol\PlayerAuthInputPacket;
use XPocketMP\network\mcpe\protocol\PlayerSkinPacket;
use XPocketMP\network\mcpe\protocol\SetLocalPlayerAsInitializedPacket;

final class SpawnResponsePacketHandler extends PacketHandler{
	/**
	 * @phpstan-param \Closure() : void $responseCallback
	 */
	public function __construct(private \Closure $responseCallback){}

	public function handleSetLocalPlayerAsInitialized(SetLocalPlayerAsInitializedPacket $packet) : bool{
		($this->responseCallback)();
		return true;
	}

	public function handlePlayerSkin(PlayerSkinPacket $packet) : bool{
		//TODO: REMOVE THIS
		//As of 1.19.60, we receive this packet during pre-spawn for no obvious reason. The skin is still sent in the
		//login packet, so we can ignore this one. If unhandled, this packet makes a huge debug spam in the log.
		return true;
	}

	public function handlePlayerAuthInput(PlayerAuthInputPacket $packet) : bool{
		//the client will send this every tick once we start sending chunks, but we don't handle it in this stage
		//this is very spammy so we filter it out
		return true;
	}
}