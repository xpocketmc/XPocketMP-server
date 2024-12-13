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
use pocketmine\entity\effect\Effect;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

/**
 * Called when a player activates a beacon with the interface.
 */
class BeaconActivateEvent extends BlockEvent implements Cancellable{
	use CancellableTrait;

	protected Effect $primaryEffect;
	protected ?Effect $secondaryEffect;

	public function __construct(Block $block, Effect $primaryEffect, ?Effect $secondaryEffect = null){
		parent::__construct($block);
		$this->primaryEffect = $primaryEffect;
		$this->secondaryEffect = $secondaryEffect;
	}

	public function getPrimaryEffect() : Effect{
		return $this->primaryEffect;
	}

	public function getSecondaryEffect() : ?Effect{
		return $this->secondaryEffect;
	}

	public function setPrimaryEffect(Effect $primary) : void{
		$this->primaryEffect = $primary;
	}

	public function setSecondaryEffect(?Effect $secondary) : void{
		$this->secondaryEffect = $secondary;
	}
}
