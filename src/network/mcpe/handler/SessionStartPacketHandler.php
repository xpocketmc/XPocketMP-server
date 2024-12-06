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

use XPocketMP\network\mcpe\NetworkSession;
use XPocketMP\network\mcpe\protocol\NetworkSettingsPacket;
use XPocketMP\network\mcpe\protocol\ProtocolInfo;
use XPocketMP\network\mcpe\protocol\RequestNetworkSettingsPacket;

final class SessionStartPacketHandler extends PacketHandler{

	/**
	 * @phpstan-param \Closure() : void $onSuccess
	 */
	public function __construct(
		private NetworkSession $session,
		private \Closure $onSuccess
	){}

	public function handleRequestNetworkSettings(RequestNetworkSettingsPacket $packet) : bool{
		$protocolVersion = $packet->getProtocolVersion();
		if(!$this->isCompatibleProtocol($protocolVersion)){
			$this->session->disconnectIncompatibleProtocol($protocolVersion);

			return true;
		}

		//TODO: we're filling in the defaults to get pre-1.19.30 behaviour back for now, but we should explore the new options in the future
		$this->session->sendDataPacket(NetworkSettingsPacket::create(
			NetworkSettingsPacket::COMPRESS_EVERYTHING,
			$this->session->getCompressor()->getNetworkId(),
			false,
			0,
			0
		));
		($this->onSuccess)();

		return true;
	}

	protected function isCompatibleProtocol(int $protocolVersion) : bool{
		return $protocolVersion === ProtocolInfo::CURRENT_PROTOCOL;
	}
}