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

namespace pocketmine\block;

use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;

/**
 * Represents a block which is unrecognized or not implemented.
 */
class UnknownBlock extends Transparent{

	public function __construct(BlockIdentifier $idInfo, BlockTypeInfo $typeInfo, private int $stateData){
		parent::__construct($idInfo, "Unknown", $typeInfo);
	}

	public function describeBlockItemState(RuntimeDataDescriber $w) : void{
		//use type instead of state, so we don't lose any information like colour
		//this might be an improperly registered plugin block
		$w->int(Block::INTERNAL_STATE_DATA_BITS, $this->stateData);
	}

	public function canBePlaced() : bool{
		return false;
	}

	public function getDrops(Item $item) : array{
		return [];
	}
}
