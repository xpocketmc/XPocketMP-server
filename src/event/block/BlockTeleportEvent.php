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

namespace XPocketMP\event\block;

use XPocketMPlock\Block;
use XPocketMP\event\Cancellable;
use XPocketMP\event\CancellableTrait;
use XPocketMP\math\Vector3;
use XPocketMP\utils\Utils;

class BlockTeleportEvent extends BlockEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		Block $block,
		private Vector3 $to
	){
		parent::__construct($block);
	}

	public function getTo() : Vector3{
		return $this->to;
	}

	public function setTo(Vector3 $to) : void{
		Utils::checkVector3NotInfOrNaN($to);
		$this->to = $to;
	}
}