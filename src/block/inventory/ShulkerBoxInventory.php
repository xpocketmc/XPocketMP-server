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

namespace pocketmine\block\inventory;

use pocketmine\block\BlockTypeIds;
use pocketmine\inventory\SimpleInventory;
use pocketmine\item\Item;
use pocketmine\item\ItemTypeIds;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\world\Position;
use pocketmine\world\sound\ShulkerBoxCloseSound;
use pocketmine\world\sound\ShulkerBoxOpenSound;
use pocketmine\world\sound\Sound;

class ShulkerBoxInventory extends SimpleInventory implements BlockInventory{
	use AnimatedBlockInventoryTrait;

	public function __construct(Position $holder){
		$this->holder = $holder;
		parent::__construct(27);
	}

	protected function getOpenSound() : Sound{
		return new ShulkerBoxOpenSound();
	}

	protected function getCloseSound() : Sound{
		return new ShulkerBoxCloseSound();
	}

	public function canAddItem(Item $item) : bool{
		$blockTypeId = ItemTypeIds::toBlockTypeId($item->getTypeId());
		if($blockTypeId === BlockTypeIds::SHULKER_BOX || $blockTypeId === BlockTypeIds::DYED_SHULKER_BOX){
			return false;
		}
		return parent::canAddItem($item);
	}

	protected function animateBlock(bool $isOpen) : void{
		$holder = $this->getHolder();

		//event ID is always 1 for a chest
		$holder->getWorld()->broadcastPacketToViewers($holder, BlockEventPacket::create(BlockPosition::fromVector3($holder), 1, $isOpen ? 1 : 0));
	}
}
