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

use pocketmine\block\utils\BlockEventHelper;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\player\Player;

class Ice extends Transparent{

	public function getLightFilter() : int{
		return 2;
	}

	public function getFrictionFactor() : float{
		return 0.98;
	}

	public function onBreak(Item $item, ?Player $player = null, array &$returnedItems = []) : bool{
		if(($player === null || $player->isSurvival()) && !$item->hasEnchantment(VanillaEnchantments::SILK_TOUCH())){
			$this->position->getWorld()->setBlock($this->position, VanillaBlocks::WATER());
			return true;
		}
		return parent::onBreak($item, $player, $returnedItems);
	}

	public function ticksRandomly() : bool{
		return true;
	}

	public function onRandomTick() : void{
		$world = $this->position->getWorld();
		if($world->getHighestAdjacentBlockLight($this->position->x, $this->position->y, $this->position->z) >= 12){
			BlockEventHelper::melt($this, VanillaBlocks::WATER());
		}
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}
}
