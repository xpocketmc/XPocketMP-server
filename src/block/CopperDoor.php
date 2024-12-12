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
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\CopperMaterial;
use pocketmine\block\utils\CopperTrait;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class CopperDoor extends Door implements CopperMaterial{
	use CopperTrait{
		onInteract as onInteractCopper;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if ($player !== null && $player->isSneaking() && $this->onInteractCopper($item, $face, $clickVector, $player, $returnedItems)) {
			//copy copper properties to other half
			$other = $this->getSide($this->top ? Facing::DOWN : Facing::UP);
			$world = $this->position->getWorld();
			if ($other instanceof CopperDoor) {
				$other->setOxidation($this->oxidation);
				$other->setWaxed($this->waxed);
				$world->setBlock($other->position, $other);
			}
			return true;
		}

		return parent::onInteract($item, $face, $clickVector, $player, $returnedItems);
	}
}
