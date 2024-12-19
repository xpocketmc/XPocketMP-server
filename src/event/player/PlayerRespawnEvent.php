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

namespace pocketmine\event\player;

use pocketmine\player\Player;
use pocketmine\utils\Utils;
use pocketmine\world\Position;

/**
 * Called when a player is respawned
 */
class PlayerRespawnEvent extends PlayerEvent{
	public function __construct(
		Player $player,
		protected Position $position
	){
		$this->player = $player;
	}

	public function getRespawnPosition() : Position{
		return $this->position;
	}

	public function setRespawnPosition(Position $position) : void{
		if(!$position->isValid()){
			throw new \InvalidArgumentException("Spawn position must reference a valid and loaded World");
		}
		Utils::checkVector3NotInfOrNaN($position);
		$this->position = $position;
	}
}
