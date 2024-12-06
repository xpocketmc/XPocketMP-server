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

namespace XPocketMP\world\particle;

use XPocketMP\math\Vector3;
use XPocketMP\network\mcpe\protocol\LevelEventPacket;
use XPocketMP\network\mcpe\protocol\types\ParticleIds;

class BlockForceFieldParticle implements Particle{
	//TODO: proper encode/decode of data
	public function __construct(private int $data = 0){}

	public function encode(Vector3 $pos) : array{
		return [LevelEventPacket::standardParticle(ParticleIds::BLOCK_FORCE_FIELD, $this->data, $pos)];
	}
}