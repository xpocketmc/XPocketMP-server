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

namespace XPocketMP\data\bedrock;

use XPocketMPlock\utils\MobHeadType;
use XPocketMP\utils\SingletonTrait;

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