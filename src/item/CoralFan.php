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

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\utils\CoralTypeTrait;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\math\Axis;
use pocketmine\math\Facing;

final class CoralFan extends Item{
	use CoralTypeTrait {
		describeBlockItemState as encodeCoralType;
	}

	public function __construct(ItemIdentifier $identifier){
		parent::__construct($identifier, VanillaBlocks::CORAL_FAN()->getName());
	}

	protected function describeState(RuntimeDataDescriber $w) : void{
		//this is aliased to ensure a compile error in case the functions in Item or Block start to differ in future
		//right now we can directly reuse encodeType from CoralTypeTrait, but that might silently stop working if Item
		//were to be altered. CoralTypeTrait was originally intended for blocks, so it's better not to assume anything.
		$this->encodeCoralType($w);
	}

	public function getBlock(?int $clickedFace = null) : Block{
		$block = $clickedFace !== null && Facing::axis($clickedFace) !== Axis::Y ? VanillaBlocks::WALL_CORAL_FAN() : VanillaBlocks::CORAL_FAN();

		return $block->setCoralType($this->coralType)->setDead($this->dead);
	}

	public function getFuelTime() : int{
		return $this->getBlock()->getFuelTime();
	}

	public function getMaxStackSize() : int{
		return $this->getBlock()->getMaxStackSize();
	}
}
