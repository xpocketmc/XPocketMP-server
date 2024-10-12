<?php

/*
 * This file is part of BedrockProtocol.
 * Copyright (C) 2014-2022 PocketMine Team <https://github.com/pmmp/BedrockProtocol>
 *
 * BedrockProtocol is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class StopSoundPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::STOP_SOUND_PACKET;

	public string $soundName;
	public bool $stopAll;
	public bool $stopLegacyMusic;

	/**
	 * @generate-create-func
	 */
	public static function create(string $soundName, bool $stopAll, bool $stopLegacyMusic) : self{
		$result = new self;
		$result->soundName = $soundName;
		$result->stopAll = $stopAll;
		$result->stopLegacyMusic = $stopLegacyMusic;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->soundName = $in->getString();
		$this->stopAll = $in->getBool();
		$this->stopLegacyMusic = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->soundName);
		$out->putBool($this->stopAll);
		$out->putBool($this->stopLegacyMusic);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleStopSound($this);
	}
}
