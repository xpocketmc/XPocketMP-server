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

namespace pocketmine\block\inventory;

use pocketmine\block\Barrel;
use pocketmine\inventory\SimpleInventory;
use pocketmine\world\Position;
use pocketmine\world\sound\BarrelCloseSound;
use pocketmine\world\sound\BarrelOpenSound;
use pocketmine\world\sound\Sound;

class BarrelInventory extends SimpleInventory implements BlockInventory{
	use AnimatedBlockInventoryTrait;

	public function __construct(Position $holder){
		$this->holder = $holder;
		parent::__construct(27);
	}

	protected function getOpenSound() : Sound{
		return new BarrelOpenSound();
	}

	protected function getCloseSound() : Sound{
		return new BarrelCloseSound();
	}

	protected function animateBlock(bool $isOpen) : void{
		$holder = $this->getHolder();
		$world = $holder->getWorld();
		$block = $world->getBlock($holder);
		if($block instanceof Barrel){
			$world->setBlock($holder, $block->setOpen($isOpen));
		}
	}
}
