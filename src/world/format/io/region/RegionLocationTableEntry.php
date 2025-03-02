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

use function range;

class RegionLocationTableEntry{
	private int $firstSector;
	/** @phpstan-var positive-int */
	private int $sectorCount;
	private int $timestamp;

	/**
	 * @throws \InvalidArgumentException
	 */
	public function __construct(int $firstSector, int $sectorCount, int $timestamp){
		if($firstSector < 0 || $firstSector >= 2 ** 24){
			throw new \InvalidArgumentException("Start sector must be positive, got $firstSector");
		}
		$this->firstSector = $firstSector;
		if($sectorCount < 1){
			throw new \InvalidArgumentException("Sector count must be positive, got $sectorCount");
		}
		$this->sectorCount = $sectorCount;
		$this->timestamp = $timestamp;
	}

	public function getFirstSector() : int{
		return $this->firstSector;
	}

	public function getLastSector() : int{
		return $this->firstSector + $this->sectorCount - 1;
	}

	/**
	 * Returns an array of sector offsets reserved by this chunk.
	 * @return int[]
	 */
	public function getUsedSectors() : array{
		return range($this->getFirstSector(), $this->getLastSector());
	}

	/**
	 * @phpstan-return positive-int
	 */
	public function getSectorCount() : int{
		return $this->sectorCount;
	}

	public function getTimestamp() : int{
		return $this->timestamp;
	}

	public function overlaps(RegionLocationTableEntry $other) : bool{
		$overlapCheck = static function(RegionLocationTableEntry $entry1, RegionLocationTableEntry $entry2) : bool{
			$entry1Last = $entry1->getLastSector();
			$entry2Last = $entry2->getLastSector();

			return (
				($entry2->firstSector >= $entry1->firstSector && $entry2->firstSector <= $entry1Last) ||
				($entry2Last >= $entry1->firstSector && $entry2Last <= $entry1Last)
			);
		};
		return $overlapCheck($this, $other) || $overlapCheck($other, $this);
	}
}
