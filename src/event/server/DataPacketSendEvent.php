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

namespace pocketmine\event\server;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\utils\Utils;

/**
 * Called when packets are sent to network sessions.
 */
class DataPacketSendEvent extends ServerEvent implements Cancellable{
	use CancellableTrait;

	/**
	 * @param NetworkSession[]    $targets
	 * @param ClientboundPacket[] $packets
	 */
	public function __construct(
		private array $targets,
		private array $packets
	){}

	/**
	 * @return NetworkSession[]
	 */
	public function getTargets() : array{
		return $this->targets;
	}

	/**
	 * @return ClientboundPacket[]
	 */
	public function getPackets() : array{
		return $this->packets;
	}

	/**
	 * @param ClientboundPacket[] $packets
	 */
	public function setPackets(array $packets) : void{
		Utils::validateArrayValueType($packets, function(ClientboundPacket $_) : void{});
		$this->packets = $packets;
	}
}
