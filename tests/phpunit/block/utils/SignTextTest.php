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

namespace pocketmine\block\utils;

use PHPUnit\Framework\TestCase;

class SignTextTest extends TestCase{

	public function testConstructorOmitLines() : void{
		$text = new SignText([1 => "test"]);
		self::assertSame("", $text->getLine(0));
		self::assertSame("test", $text->getLine(1));
		self::assertSame("", $text->getLine(2));
		self::assertSame("", $text->getLine(3));
	}
}
