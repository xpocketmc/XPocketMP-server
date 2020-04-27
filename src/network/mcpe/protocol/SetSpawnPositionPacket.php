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
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\protocol\serializer\NetworkBinaryStream;

class SetSpawnPositionPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_SPAWN_POSITION_PACKET;

	public const TYPE_PLAYER_SPAWN = 0;
	public const TYPE_WORLD_SPAWN = 1;

	/** @var int */
	public $spawnType;
	/** @var int */
	public $x;
	/** @var int */
	public $y;
	/** @var int */
	public $z;
	/** @var bool */
	public $spawnForced;

	public static function playerSpawn(int $x, int $y, int $z, bool $forced) : self{
		$result = new self;
		$result->spawnType = self::TYPE_PLAYER_SPAWN;
		[$result->x, $result->y, $result->z] = [$x, $y, $z];
		$result->spawnForced = $forced;
		return $result;
	}

	public static function worldSpawn(int $x, int $y, int $z) : self{
		$result = new self;
		$result->spawnType = self::TYPE_WORLD_SPAWN;
		[$result->x, $result->y, $result->z] = [$x, $y, $z];
		return $result;
	}

	protected function decodePayload(NetworkBinaryStream $in) : void{
		$this->spawnType = $in->getVarInt();
		$in->getBlockPosition($this->x, $this->y, $this->z);
		$this->spawnForced = $in->getBool();
	}

	protected function encodePayload(NetworkBinaryStream $out) : void{
		$out->putVarInt($this->spawnType);
		$out->putBlockPosition($this->x, $this->y, $this->z);
		$out->putBool($this->spawnForced);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetSpawnPosition($this);
	}
}
