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

namespace pocketmine\block;

use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use function mt_rand;

class SeaLantern extends Transparent{
	public const MINIMUM_DROPS = 2;
	public const MAXIMUM_DROPS = 3;

	public function getLightLevel() : int{
		return 15;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			VanillaItems::PRISMARINE_CRYSTALS()->setCount(mt_rand(self::MINIMUM_DROPS, self::MAXIMUM_DROPS))
		];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}

	public function isAffectedByFortune() : bool{
		return true;
	}

	public function getFortuneDrops(Item $item) : array{
		$fortuneEnchantment = VanillaEnchantments::FORTUNE();
		return $fortuneEnchantment->discreteDrops(
			VanillaItems::PRISMARINE_CRYSTALS(),
			self::MINIMUM_DROPS,
			self::MAXIMUM_DROPS,
			$item->getEnchantmentLevel($fortuneEnchantment),
			5
		);
	}
}
