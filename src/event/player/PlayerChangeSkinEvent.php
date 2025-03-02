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

namespace pocketmine\event\player;

use pocketmine\entity\Skin;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\player\Player;

/**
 * Called when a player changes their skin in-game.
 */
class PlayerChangeSkinEvent extends PlayerEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		Player $player,
		private Skin $oldSkin,
		private Skin $newSkin
	){
		$this->player = $player;
	}

	public function getOldSkin() : Skin{
		return $this->oldSkin;
	}

	public function getNewSkin() : Skin{
		return $this->newSkin;
	}

	public function setNewSkin(Skin $skin) : void{
		$this->newSkin = $skin;
	}
}
