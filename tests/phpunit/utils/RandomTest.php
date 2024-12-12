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

namespace pocketmine\utils;

use PHPUnit\Framework\TestCase;

class RandomTest extends TestCase{

	public function testNextSignedIntReturnsSignedInts() : void{
		//use a known seed which should definitely produce negatives
		$random = new Random(0);
		$negatives = false;

		for($i = 0; $i < 100; ++$i){
			if($random->nextSignedInt() < 0){
				$negatives = true;
				break;
			}
		}
		self::assertTrue($negatives);
	}
}
