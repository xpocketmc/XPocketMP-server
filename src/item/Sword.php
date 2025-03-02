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

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\entity\Entity;

class Sword extends TieredTool{

	public function getBlockToolType() : int{
		return BlockToolType::SWORD;
	}

	public function getAttackPoints() : int{
		return $this->tier->getBaseAttackPoints();
	}

	public function getBlockToolHarvestLevel() : int{
		return 1;
	}

	public function getMiningEfficiency(bool $isCorrectTool) : float{
		return parent::getMiningEfficiency($isCorrectTool) * 1.5; //swords break any block 1.5x faster than hand
	}

	protected function getBaseMiningEfficiency() : float{
		return 10;
	}

	public function onDestroyBlock(Block $block, array &$returnedItems) : bool{
		if(!$block->getBreakInfo()->breaksInstantly()){
			return $this->applyDamage(2);
		}
		return false;
	}

	public function onAttackEntity(Entity $victim, array &$returnedItems) : bool{
		return $this->applyDamage(1);
	}
}
