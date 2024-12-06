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

namespace XPocketMP\event\server;

use XPocketMP\event\Cancellable;
use XPocketMP\event\CancellableTrait;
use XPocketMP\network\mcpe\NetworkSession;
use XPocketMP\network\mcpe\protocol\ClientboundPacket;
use XPocketMP\utils\Utils;

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