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

namespace pocketmine\item;

use PHPUnit\Framework\TestCase;
use pocketmine\utils\Utils;
use function array_unique;
use function max;

class ItemTypeIdsTest extends TestCase{

	public function testFirstUnused() : void{
		$reflect = new \ReflectionClass(ItemTypeIds::class);

		$constants = $reflect->getConstants();
		unset($constants['FIRST_UNUSED_ITEM_ID']);

		self::assertSame($reflect->getConstant('FIRST_UNUSED_ITEM_ID'), max($constants) + 1, "FIRST_UNUSED_ITEM_ID must be one higher than the highest fixed type ID");
	}

	public function testNoDuplicates() : void{
		$idTable = (new \ReflectionClass(ItemTypeIds::class))->getConstants();

		self::assertSameSize($idTable, array_unique($idTable), "Every ItemTypeID must be unique");
	}

	public function testVanillaItemsParity() : void{
		$reflect = new \ReflectionClass(ItemTypeIds::class);

		foreach(Utils::stringifyKeys(VanillaItems::getAll()) as $name => $item){
			if($item instanceof ItemBlock){
				continue;
			}
			$expected = $item->getTypeId();
			$actual = $reflect->getConstant($name);
			self::assertSame($expected, $actual, "VanillaItems::$name() type ID does not match ItemTypeIds::$name");
		}
	}
}
