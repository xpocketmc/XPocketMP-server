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

namespace pocketmine\data\bedrock;

use pocketmine\item\MedicineType;
use pocketmine\utils\SingletonTrait;

final class MedicineTypeIdMap{
	use SingletonTrait;
	/** @phpstan-use IntSaveIdMapTrait<MedicineType> */
	use IntSaveIdMapTrait;

	private function __construct(){
		foreach(MedicineType::cases() as $case){
			$this->register(match($case){
				MedicineType::ANTIDOTE => MedicineTypeIds::ANTIDOTE,
				MedicineType::ELIXIR => MedicineTypeIds::ELIXIR,
				MedicineType::EYE_DROPS => MedicineTypeIds::EYE_DROPS,
				MedicineType::TONIC => MedicineTypeIds::TONIC,
			}, $case);
		}
	}
}
