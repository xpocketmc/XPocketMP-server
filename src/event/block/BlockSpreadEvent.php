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

/**
 * Called when a block spreads to another block, such as grass spreading to nearby dirt blocks.
 */
class BlockSpreadEvent extends BaseBlockChangeEvent{

	/**
	 * @param Block $block    Block being replaced (TODO: rename this)
	 * @param Block $source   Origin of the spread
	 * @param Block $newState Replacement block
	 */
	public function __construct(
		Block $block,
		private Block $source,
		Block $newState
	){
		parent::__construct($block, $newState);
	}

	public function getSource() : Block{
		return $this->source;
	}
}
