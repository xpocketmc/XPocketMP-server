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

namespace pocketmine\item;

use PHPUnit\Framework\TestCase;
use pocketmine\block\utils\BannerPatternLayer;
use pocketmine\block\utils\BannerPatternType;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use function assert;

final class BannerTest extends TestCase{

	public function testBannerPatternSaveRestore() : void{
		$item = VanillaBlocks::BANNER()->asItem();
		assert($item instanceof Banner);
		$item->setPatterns([
			new BannerPatternLayer(BannerPatternType::FLOWER, DyeColor::RED)
		]);
		$data = $item->nbtSerialize();

		$item2 = Item::nbtDeserialize($data);
		self::assertTrue($item->equalsExact($item2));
		self::assertInstanceOf(Banner::class, $item2);
		$patterns = $item2->getPatterns();
		self::assertCount(1, $patterns);
		self::assertTrue(BannerPatternType::FLOWER === $patterns[0]->getType());
	}
}
