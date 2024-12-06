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

namespace XPocketMPlock\utils;

use XPocketMPlock\BlockIdentifier;
use XPocketMPlock\BlockTypeInfo;

trait WoodTypeTrait{
	private WoodType $woodType; //immutable for now

	public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo, WoodType $woodType){
		$this->woodType = $woodType;
		parent::__construct($idInfo, $name, $typeInfo);
	}

	public function getWoodType() : WoodType{
		return $this->woodType;
	}
}