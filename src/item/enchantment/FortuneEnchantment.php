<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\item\enchantment;

use pocketmine\item\Item;
use function min;
use function mt_getrandmax;
use function mt_rand;
use const PHP_INT_MAX;

class FortuneEnchantment extends Enchantment{
	/**
	 * Gives a weight of 2 to a normal drop chance and adds a weight of 1 for each extra drop multiplier.
	 *
	 * @return Item[]
	 */
	public function mineralDrops(Item $item, int $min, int $max, int $fortuneLevel) : array{
		$count = mt_rand($min, $max);
		$chanceForNoMoreDrop = 2 / ($fortuneLevel + 2);
		$rdm = mt_rand() / mt_getrandmax();
		if ($fortuneLevel > 0 && $rdm > $chanceForNoMoreDrop) {
			$count = mt_rand($min, $max * ($fortuneLevel + 1));
		}
		return [
			$item->setCount($count)
		];
	}

	/**
	 * Discreet drop, increases the maximum number of items that can be dropped by the fortune level.
	 *
	 * @param int $maximumDropLimitation As minecraft doc, this is the maximum number of drops that can be dropped by this enchantment.
	 *                                   If a drop higher than these maximums is rolled, it is rounded down to the capacity.
	 * @return Item[]
	 */
	public function discreteDrops(Item $item, int $min, int $max, int $fortuneLevel, int $maximumDropLimitation = PHP_INT_MAX) : array{
		$max = min(
			$maximumDropLimitation,
			$max + $fortuneLevel
		);
		$count = mt_rand($min, $max);
		return [
			$item->setCount($count)
		];
	}
}
