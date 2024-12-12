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
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\world\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use function intdiv;
use function min;

class XpLevelUpSound implements Sound{
	public function __construct(private int $xpLevel){}

	public function getXpLevel() : int{
		return $this->xpLevel;
	}

	public function encode(Vector3 $pos) : array{
		//No idea why such odd numbers, but this works...
		//TODO: check arbitrary volume
		return [LevelSoundEventPacket::nonActorSound(LevelSoundEvent::LEVELUP, $pos, false, 0x10000000 * intdiv(min(30, $this->xpLevel), 5))];
	}
}
