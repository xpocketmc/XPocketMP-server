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

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static NoteInstrument BANJO()
 * @method static NoteInstrument BASS_DRUM()
 * @method static NoteInstrument BELL()
 * @method static NoteInstrument BIT()
 * @method static NoteInstrument CHIME()
 * @method static NoteInstrument CLICKS_AND_STICKS()
 * @method static NoteInstrument COW_BELL()
 * @method static NoteInstrument DIDGERIDOO()
 * @method static NoteInstrument DOUBLE_BASS()
 * @method static NoteInstrument FLUTE()
 * @method static NoteInstrument GUITAR()
 * @method static NoteInstrument IRON_XYLOPHONE()
 * @method static NoteInstrument PIANO()
 * @method static NoteInstrument PLING()
 * @method static NoteInstrument SNARE()
 * @method static NoteInstrument XYLOPHONE()
 */
enum NoteInstrument{
	use LegacyEnumShimTrait;

	case PIANO;
	case BASS_DRUM;
	case SNARE;
	case CLICKS_AND_STICKS;
	case DOUBLE_BASS;
	case BELL;
	case FLUTE;
	case CHIME;
	case GUITAR;
	case XYLOPHONE;
	case IRON_XYLOPHONE;
	case COW_BELL;
	case DIDGERIDOO;
	case BIT;
	case BANJO;
	case PLING;
}
