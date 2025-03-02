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
use pocketmine\block\utils\CoralTypeTrait;
use pocketmine\block\utils\SupportType;
use pocketmine\item\Item;
use function mt_rand;

abstract class BaseCoral extends Transparent{
	use CoralTypeTrait;

	public function onNearbyBlockChange() : void{
		if(!$this->dead){
			$this->position->getWorld()->scheduleDelayedBlockUpdate($this->position, mt_rand(40, 200));
		}
	}

	public function onScheduledUpdate() : void{
		if(!$this->dead && !$this->isCoveredWithWater()){
			BlockEventHelper::die($this, (clone $this)->setDead(true));
		}
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}

	public function isSolid() : bool{ return false; }

	protected function isCoveredWithWater() : bool{
		$world = $this->position->getWorld();

		$hasWater = false;
		foreach($this->position->sides() as $vector3){
			if($world->getBlock($vector3) instanceof Water){
				$hasWater = true;
				break;
			}
		}

		//TODO: check water inside the block itself (not supported on the API yet)
		return $hasWater;
	}

	protected function recalculateCollisionBoxes() : array{ return []; }

	public function getSupportType(int $facing) : SupportType{
		return SupportType::NONE;
	}
}
