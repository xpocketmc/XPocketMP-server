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
 * Developed by ClousClouds, PMMP Team
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\data\bedrock;

use pocketmine\item\SuspiciousStewType;
use pocketmine\utils\SingletonTrait;

final class SuspiciousStewTypeIdMap{
	use SingletonTrait;
	/** @phpstan-use IntSaveIdMapTrait<SuspiciousStewType> */
	use IntSaveIdMapTrait;

	private function __construct(){
		foreach(SuspiciousStewType::cases() as $case){
			$this->register(match($case){
				SuspiciousStewType::POPPY => SuspiciousStewTypeIds::POPPY,
				SuspiciousStewType::CORNFLOWER => SuspiciousStewTypeIds::CORNFLOWER,
				SuspiciousStewType::TULIP => SuspiciousStewTypeIds::TULIP,
				SuspiciousStewType::AZURE_BLUET => SuspiciousStewTypeIds::AZURE_BLUET,
				SuspiciousStewType::LILY_OF_THE_VALLEY => SuspiciousStewTypeIds::LILY_OF_THE_VALLEY,
				SuspiciousStewType::DANDELION => SuspiciousStewTypeIds::DANDELION,
				SuspiciousStewType::BLUE_ORCHID => SuspiciousStewTypeIds::BLUE_ORCHID,
				SuspiciousStewType::ALLIUM => SuspiciousStewTypeIds::ALLIUM,
				SuspiciousStewType::OXEYE_DAISY => SuspiciousStewTypeIds::OXEYE_DAISY,
				SuspiciousStewType::WITHER_ROSE => SuspiciousStewTypeIds::WITHER_ROSE,
			}, $case);
		}

	}
}
