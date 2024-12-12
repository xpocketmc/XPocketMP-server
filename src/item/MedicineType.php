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
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static MedicineType ANTIDOTE()
 * @method static MedicineType ELIXIR()
 * @method static MedicineType EYE_DROPS()
 * @method static MedicineType TONIC()
 */
enum MedicineType{
	use LegacyEnumShimTrait;

	case ANTIDOTE;
	case ELIXIR;
	case EYE_DROPS;
	case TONIC;

	/**
	 * @phpstan-return array{0: string, 1: Effect}
	 */
	private function getMetadata() : array{
		//cache not required here - VanillaEffects always returns the same object
		return match($this){
			self::ANTIDOTE => ['Antidote', VanillaEffects::POISON()],
			self::ELIXIR => ['Elixir', VanillaEffects::WEAKNESS()],
			self::EYE_DROPS => ['Eye Drops', VanillaEffects::BLINDNESS()],
			self::TONIC => ['Tonic', VanillaEffects::NAUSEA()]
		};
	}

	public function getDisplayName() : string{ return $this->getMetadata()[0]; }

	public function getCuredEffect() : Effect{ return $this->getMetadata()[1]; }
}
