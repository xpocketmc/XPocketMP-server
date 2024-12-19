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

namespace pocketmine\network\mcpe\raklib;

use pocketmine\network\mcpe\PacketSender;

class RakLibPacketSender implements PacketSender{
	private bool $closed = false;

	public function __construct(
		private int $sessionId,
		private RakLibInterface $handler
	){}

	public function send(string $payload, bool $immediate, ?int $receiptId) : void{
		if(!$this->closed){
			$this->handler->putPacket($this->sessionId, $payload, $immediate, $receiptId);
		}
	}

	public function close(string $reason = "unknown reason") : void{
		if(!$this->closed){
			$this->closed = true;
			$this->handler->close($this->sessionId);
		}
	}
}
