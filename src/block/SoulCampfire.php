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

namespace pocketmine\block;

use pocketmine\crafting\FurnaceType;
use pocketmine\item\Item;

class SoulCampfire extends Campfire{

	public function getLightLevel() : int{
		return $this->lit ? 10 : 0;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			VanillaBlocks::SOUL_SOIL()->asItem()
		];
	}

	protected function getEntityCollisionDamage() : int{
		return 2;
	}

	protected function getFurnaceType() : FurnaceType{
		return FurnaceType::SOUL_CAMPFIRE;
	}
}
