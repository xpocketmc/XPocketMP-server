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

namespace XPocketMP\world\sound;

use XPocketMPlock\Block;
use XPocketMP\math\Vector3;
use XPocketMP\network\mcpe\convert\TypeConverter;
use XPocketMP\network\mcpe\protocol\LevelSoundEventPacket;
use XPocketMP\network\mcpe\protocol\types\LevelSoundEvent;

final class ItemUseOnBlockSound implements Sound{

	public function __construct(
		private Block $block
	){}

	public function getBlock() : Block{ return $this->block; }

	public function encode(Vector3 $pos) : array{
		return [LevelSoundEventPacket::nonActorSound(
			LevelSoundEvent::ITEM_USE_ON,
			$pos,
			false,
			TypeConverter::getInstance()->getBlockTranslator()->internalIdToNetworkId($this->block->getStateId())
		)];
	}
}