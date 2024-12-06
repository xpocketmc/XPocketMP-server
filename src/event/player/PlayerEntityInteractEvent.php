<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author XPocketMP Team
 * @link http://www.xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace XPocketMP\event\player;

use XPocketMP\entity\Entity;
use XPocketMP\event\Cancellable;
use XPocketMP\event\CancellableTrait;
use XPocketMP\math\Vector3;
use XPocketMP\player\Player;

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