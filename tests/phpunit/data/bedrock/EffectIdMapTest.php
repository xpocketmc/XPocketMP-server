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

namespace pocketmine\data\bedrock;

use PHPUnit\Framework\TestCase;
use pocketmine\entity\effect\VanillaEffects;

class EffectIdMapTest extends TestCase{

	public function testAllEffectsMapped() : void{
		foreach(VanillaEffects::getAll() as $e){
			$id = EffectIdMap::getInstance()->toId($e);
			$e2 = EffectIdMap::getInstance()->fromId($id);
			self::assertTrue($e === $e2);
		}
	}
}
