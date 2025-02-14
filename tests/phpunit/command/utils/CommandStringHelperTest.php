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

namespace pocketmine\command\utils;

use PHPUnit\Framework\TestCase;

class CommandStringHelperTest extends TestCase{

	public static function parseQuoteAwareProvider() : \Generator{
		yield [
			'give "steve jobs" apple',
			['give', 'steve jobs', 'apple']
		];
		yield [
			'say \"escaped\"',
			['say', '"escaped"']
		];
		yield [
			'say This message contains \"escaped quotes\", which are ignored',
			['say', 'This', 'message', 'contains', '"escaped', 'quotes",', 'which', 'are', 'ignored']
		];
		yield [
			'say dontspliton"half"wayquotes',
			['say', 'dontspliton"half"wayquotes']
		];
	}

	/**
	 * @dataProvider parseQuoteAwareProvider
	 * @param string[] $expected
	 */
	public function testParseQuoteAware(string $commandLine, array $expected) : void{
		$actual = CommandStringHelper::parseQuoteAware($commandLine);

		self::assertSame($expected, $actual);
	}
}
