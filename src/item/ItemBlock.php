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

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\data\runtime\RuntimeDataDescriber;

/**
 * Class used for Items that directly represent blocks, such as stone, dirt, wood etc.
 *
 * This should NOT be used for items which are merely *associated* with blocks (e.g. seeds are not wheat crops; they
 * just place wheat crops when used on the ground).
 */
final class ItemBlock extends Item{
	public function __construct(
		private Block $block
	){
		parent::__construct(ItemIdentifier::fromBlock($block), $block->getName(), $block->getEnchantmentTags());
	}

	protected function describeState(RuntimeDataDescriber $w) : void{
		$this->block->describeBlockItemState($w);
	}

	public function getBlock(?int $clickedFace = null) : Block{
		return clone $this->block;
	}

	public function getFuelTime() : int{
		return $this->block->getFuelTime();
	}

	public function isFireProof() : bool{
		return $this->block->isFireProofAsItem();
	}

	public function getMaxStackSize() : int{
		return $this->block->getMaxStackSize();
	}

	public function isNull() : bool{
		//TODO: we really shouldn't need to treat air as a special case here
		//this is needed because the "null" empty slot item is represented by an air block, but there's no real reason
		//why air should be needed at all. A separate special item type (or actual null) should be used instead, but
		//this would cause a lot of BC breaks, so we can't do it yet.
		return parent::isNull() || $this->block->getTypeId() === BlockTypeIds::AIR;
	}
}
