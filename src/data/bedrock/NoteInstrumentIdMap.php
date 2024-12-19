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
 * Developed: ClousClouds, PMMP Team
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\data\bedrock;

use pocketmine\utils\SingletonTrait;
use pocketmine\world\sound\NoteInstrument;

final class NoteInstrumentIdMap{
	use SingletonTrait;
	/** @phpstan-use IntSaveIdMapTrait<NoteInstrument> */
	use IntSaveIdMapTrait;

	private function __construct(){
		foreach(NoteInstrument::cases() as $case){
			$this->register(match($case){
				NoteInstrument::PIANO => 0,
				NoteInstrument::BASS_DRUM => 1,
				NoteInstrument::SNARE => 2,
				NoteInstrument::CLICKS_AND_STICKS => 3,
				NoteInstrument::DOUBLE_BASS => 4,
				NoteInstrument::BELL => 5,
				NoteInstrument::FLUTE => 6,
				NoteInstrument::CHIME => 7,
				NoteInstrument::GUITAR => 8,
				NoteInstrument::XYLOPHONE => 9,
				NoteInstrument::IRON_XYLOPHONE => 10,
				NoteInstrument::COW_BELL => 11,
				NoteInstrument::DIDGERIDOO => 12,
				NoteInstrument::BIT => 13,
				NoteInstrument::BANJO => 14,
				NoteInstrument::PLING => 15,
			}, $case);
		}
	}
}
