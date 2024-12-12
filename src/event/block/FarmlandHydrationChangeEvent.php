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

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\block\Farmland;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

/**
 * Called when farmland hydration is updated.
 */
class FarmlandHydrationChangeEvent extends BlockEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		Block $block,
		private int $oldHydration,
		private int $newHydration,
	){
		parent::__construct($block);
	}

	public function getOldHydration() : int{
		return $this->oldHydration;
	}

	public function getNewHydration() : int{
		return $this->newHydration;
	}

	public function setNewHydration(int $hydration) : void{
		if($hydration < 0 || $hydration > Farmland::MAX_WETNESS){
			throw new \InvalidArgumentException("Hydration must be in range 0 ... " . Farmland::MAX_WETNESS);
		}
		$this->newHydration = $hydration;
	}
}
