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

namespace pocketmine\block;

use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

class Wheat extends Crops{

	public function getDropsForCompatibleTool(Item $item) : array{
		if($this->age >= self::MAX_AGE){
			return [
				VanillaItems::WHEAT(),
				VanillaItems::WHEAT_SEEDS()->setCount(FortuneDropHelper::binomial($item, 0))
			];
		}else{
			return [
				VanillaItems::WHEAT_SEEDS()
			];
		}
	}

	public function asItem() : Item{
		return VanillaItems::WHEAT_SEEDS();
	}
}
