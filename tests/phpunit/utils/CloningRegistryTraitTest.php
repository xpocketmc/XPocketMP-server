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
 * Developed: ClousClouds, PMMP Team
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\utils;

use PHPUnit\Framework\TestCase;

final class CloningRegistryTraitTest extends TestCase{

	/**
	 * @phpstan-return \Generator<int, array{\Closure() : \stdClass}, void, void>
	 */
	public static function cloningRegistryMembersProvider() : \Generator{
		yield [function() : \stdClass{ return TestCloningRegistry::TEST1(); }];
		yield [function() : \stdClass{ return TestCloningRegistry::TEST2(); }];
		yield [function() : \stdClass{ return TestCloningRegistry::TEST3(); }];
	}

	/**
	 * @dataProvider cloningRegistryMembersProvider
	 * @phpstan-param \Closure() : \stdClass $provider
	 */
	public function testEachMemberClone(\Closure $provider) : void{
		self::assertNotSame($provider(), $provider(), "Cloning registry should never return the same object twice");
	}

	public function testGetAllClone() : void{
		$list1 = TestCloningRegistry::getAll();
		$list2 = TestCloningRegistry::getAll();
		foreach($list1 as $k => $member){
			self::assertNotSame($member, $list2[$k], "VanillaBlocks ought to clone its members");
		}
	}
}
