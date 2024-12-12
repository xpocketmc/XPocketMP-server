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
 * Called when a new block forms, usually as the result of some action.
 * This could be things like obsidian forming due to collision of lava and water.
 */
class BlockFormEvent extends BaseBlockChangeEvent{

	public function __construct(
		Block $block,
		Block $newState,
		private Block $causingBlock
	){
		parent::__construct($block, $newState);
	}

	/**
	 * Returns the block which caused the target block to form into a new state.
	 */
	public function getCausingBlock() : Block{
		return $this->causingBlock;
	}
}
