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

namespace pocketmine\world\format\io\region;

use PHPUnit\Framework\TestCase;
use function sprintf;

class RegionLocationTableEntryTest extends TestCase{

	/**
	 * @phpstan-return \Generator<int, array{RegionLocationTableEntry, RegionLocationTableEntry, bool}, void, void>
	 */
	public static function overlapDataProvider() : \Generator{
		yield [new RegionLocationTableEntry(2, 1, 0), new RegionLocationTableEntry(2, 1, 0), true];
		yield [new RegionLocationTableEntry(2, 1, 0), new RegionLocationTableEntry(3, 1, 0), false];
		yield [new RegionLocationTableEntry(2, 2, 0), new RegionLocationTableEntry(3, 2, 0), true];
		yield [new RegionLocationTableEntry(2, 2, 0), new RegionLocationTableEntry(4, 2, 0), false];
		yield [new RegionLocationTableEntry(2, 2, 0), new RegionLocationTableEntry(2, 1, 0), true];
		yield [new RegionLocationTableEntry(2, 4, 0), new RegionLocationTableEntry(3, 1, 0), true];
	}

	/**
	 * @dataProvider overlapDataProvider
	 */
	public function testOverlap(RegionLocationTableEntry $entry1, RegionLocationTableEntry $entry2, bool $overlaps) : void{
		$stringify = (fn(RegionLocationTableEntry $entry) : string => sprintf("entry first=%d last=%d size=%d", $entry->getFirstSector(), $entry->getLastSector(), $entry->getSectorCount()));
		self::assertSame($overlaps, $entry1->overlaps($entry2), $stringify($entry1) . " expected to " . ($overlaps ? "overlap" : "not overlap") . " with " . $stringify($entry2));
		self::assertSame($overlaps, $entry2->overlaps($entry1), $stringify($entry2) . " expected to " . ($overlaps ? "overlap" : "not overlap") . " with " . $stringify($entry1));
	}
}
