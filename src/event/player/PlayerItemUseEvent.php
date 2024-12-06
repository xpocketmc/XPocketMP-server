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

use XPocketMP\event\Cancellable;
use XPocketMP\event\CancellableTrait;
use XPocketMP\item\Item;
use XPocketMP\math\Vector3;
use XPocketMP\player\Player;

/**
 * Called when a player uses its held item, for example when throwing a projectile.
 */
class PlayerItemUseEvent extends PlayerEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		Player $player,
		private Item $item,
		private Vector3 $directionVector
	){
		$this->player = $player;
	}

	/**
	 * Returns the item used.
	 */
	public function getItem() : Item{
		return clone $this->item;
	}

	/**
	 * Returns the direction the player is aiming when activating this item. Used for projectile direction.
	 */
	public function getDirectionVector() : Vector3{
		return $this->directionVector;
	}
}