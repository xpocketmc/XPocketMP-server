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
use pocketmine\block\BlockTypeIds;
use pocketmine\entity\Location;
use pocketmine\entity\object\EndCrystal as EntityEndCrystal;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use function count;

class EndCrystal extends Item{

	public function onInteractBlock(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, array &$returnedItems) : ItemUseResult{
		if($blockClicked->getTypeId() === BlockTypeIds::OBSIDIAN || $blockClicked->getTypeId() === BlockTypeIds::BEDROCK){
			$pos = $blockClicked->getPosition();
			$world = $pos->getWorld();
			$bb = AxisAlignedBB::one()
				->offset($pos->getX(), $pos->getY(), $pos->getZ())
				->extend(Facing::UP, 1);
			if(
				count($world->getNearbyEntities($bb)) === 0 &&
				$blockClicked->getSide(Facing::UP)->getTypeId() === BlockTypeIds::AIR &&
				$blockClicked->getSide(Facing::UP, 2)->getTypeId() === BlockTypeIds::AIR
			){
				$crystal = new EntityEndCrystal(Location::fromObject($pos->add(0.5, 1, 0.5), $world));
				$crystal->spawnToAll();

				$this->pop();
				return ItemUseResult::SUCCESS;
			}
		}
		return ItemUseResult::NONE;
	}
}
