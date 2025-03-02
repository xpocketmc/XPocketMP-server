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

namespace pocketmine\network\mcpe\handler;

use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;
use pocketmine\network\mcpe\protocol\PlayerSkinPacket;
use pocketmine\network\mcpe\protocol\SetLocalPlayerAsInitializedPacket;

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
