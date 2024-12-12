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

namespace pocketmine\event\player;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

/**
 * Called when a player interacts with an entity (e.g. shearing a sheep, naming a mob using a nametag).
 */
class PlayerEntityInteractEvent extends PlayerEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		Player $player,
		private Entity $entity,
		private Vector3 $clickPos
	){
		$this->player = $player;
	}

	public function getEntity() : Entity{
		return $this->entity;
	}

	/**
	 * Returns the absolute coordinates of the click. This is usually on the surface of the entity's hitbox.
	 */
	public function getClickPosition() : Vector3{
		return $this->clickPos;
	}
}
