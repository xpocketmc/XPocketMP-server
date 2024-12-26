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

use pocketmine\entity\Living;
use pocketmine\player\Player;

abstract class Food extends Item implements FoodSourceItem{
	public function requiresHunger() : bool{
		return true;
	}

	public function getResidue() : Item{
		return VanillaItems::AIR();
	}

	public function getAdditionalEffects() : array{
		return [];
	}

	public function onConsume(Living $consumer) : void{

	}

	public function canStartUsingItem(Player $player) : bool{
		return !$this->requiresHunger() || $player->getHungerManager()->isHungry();
	}
}
