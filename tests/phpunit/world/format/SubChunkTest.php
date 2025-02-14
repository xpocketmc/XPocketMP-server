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

namespace pocketmine\world\format;

use PHPUnit\Framework\TestCase;
use pocketmine\data\bedrock\BiomeIds;

class SubChunkTest extends TestCase{

	/**
	 * Test that a cloned SubChunk instance doesn't influence the original
	 */
	public function testClone() : void{
		$sub1 = new SubChunk(0, [], new PalettedBlockArray(BiomeIds::OCEAN));

		$sub1->setBlockStateId(0, 0, 0, 1);
		$sub1->getBlockLightArray()->set(0, 0, 0, 1);
		$sub1->getBlockSkyLightArray()->set(0, 0, 0, 1);

		$sub2 = clone $sub1;

		$sub2->setBlockStateId(0, 0, 0, 2);
		$sub2->getBlockLightArray()->set(0, 0, 0, 2);
		$sub2->getBlockSkyLightArray()->set(0, 0, 0, 2);

		self::assertNotSame($sub1->getBlockStateId(0, 0, 0), $sub2->getBlockStateId(0, 0, 0));
		self::assertNotSame($sub1->getBlockLightArray()->get(0, 0, 0), $sub2->getBlockLightArray()->get(0, 0, 0));
		self::assertNotSame($sub1->getBlockSkyLightArray()->get(0, 0, 0), $sub2->getBlockSkyLightArray()->get(0, 0, 0));
	}
}
