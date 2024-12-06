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

namespace XPocketMP\entity\animation;

use XPocketMP\entity\projectile\Arrow;
use XPocketMP\network\mcpe\protocol\ActorEventPacket;
use XPocketMP\network\mcpe\protocol\types\ActorEvent;

class ArrowShakeAnimation implements Animation{

	public function __construct(
		private Arrow $arrow,
		private int $durationInTicks
	){}

	public function encode() : array{
		return [
			ActorEventPacket::create($this->arrow->getId(), ActorEvent::ARROW_SHAKE, $this->durationInTicks)
		];
	}
}