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

use XPocketMP\entity\Living;
use XPocketMP\item\Item;
use XPocketMP\network\mcpe\convert\TypeConverter;
use XPocketMP\network\mcpe\protocol\ActorEventPacket;
use XPocketMP\network\mcpe\protocol\types\ActorEvent;

final class ConsumingItemAnimation implements Animation{

	public function __construct(
		private Living $entity,
		private Item $item
	){}

	public function encode() : array{
		[$netId, $netData] = TypeConverter::getInstance()->getItemTranslator()->toNetworkId($this->item);
		return [
			//TODO: need to check the data values
			ActorEventPacket::create($this->entity->getId(), ActorEvent::EATING_ITEM, ($netId << 16) | $netData)
		];
	}
}