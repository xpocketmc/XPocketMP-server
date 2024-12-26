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

use pocketmine\math\Facing;

final class SoulFire extends BaseFire{

	public function getLightLevel() : int{
		return 10;
	}

	protected function getFireDamage() : int{
		return 2;
	}

	public static function canBeSupportedBy(Block $block) : bool{
		//TODO: this really ought to use some kind of tag system
		$id = $block->getTypeId();
		return $id === BlockTypeIds::SOUL_SAND || $id === BlockTypeIds::SOUL_SOIL;
	}

	public function onNearbyBlockChange() : void{
		if(!self::canBeSupportedBy($this->getSide(Facing::DOWN))){
			$this->position->getWorld()->setBlock($this->position, VanillaBlocks::AIR());
		}
	}
}
