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

use pocketmine\block\utils\MobHeadType;
use pocketmine\utils\SingletonTrait;

final class MobHeadTypeIdMap{
	use SingletonTrait;
	/** @phpstan-use IntSaveIdMapTrait<MobHeadType> */
	use IntSaveIdMapTrait;

	private function __construct(){
		foreach(MobHeadType::cases() as $case){
			$this->register(match($case){
				MobHeadType::SKELETON => 0,
				MobHeadType::WITHER_SKELETON => 1,
				MobHeadType::ZOMBIE => 2,
				MobHeadType::PLAYER => 3,
				MobHeadType::CREEPER => 4,
				MobHeadType::DRAGON => 5,
				MobHeadType::PIGLIN => 6,
			}, $case);
		}
	}
}
