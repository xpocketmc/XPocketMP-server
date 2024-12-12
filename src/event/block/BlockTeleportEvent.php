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
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\math\Vector3;
use pocketmine\utils\Utils;

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
