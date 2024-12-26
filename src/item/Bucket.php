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
use pocketmine\block\Liquid;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class Bucket extends Item{

	public function getMaxStackSize() : int{
		return 16;
	}

	public function onInteractBlock(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, array &$returnedItems) : ItemUseResult{
		//TODO: move this to generic placement logic
		if($blockClicked instanceof Liquid && $blockClicked->isSource()){
			$stack = clone $this;
			$stack->pop();

			$resultItem = match($blockClicked->getTypeId()){
				BlockTypeIds::LAVA => VanillaItems::LAVA_BUCKET(),
				BlockTypeIds::WATER => VanillaItems::WATER_BUCKET(),
				default => null
			};
			if($resultItem === null){
				return ItemUseResult::FAIL;
			}

			$ev = new PlayerBucketFillEvent($player, $blockReplace, $face, $this, $resultItem);
			$ev->call();
			if(!$ev->isCancelled()){
				$player->getWorld()->setBlock($blockClicked->getPosition(), VanillaBlocks::AIR());
				$player->getWorld()->addSound($blockClicked->getPosition()->add(0.5, 0.5, 0.5), $blockClicked->getBucketFillSound());

				$this->pop();
				$returnedItems[] = $ev->getItem();
				return ItemUseResult::SUCCESS;
			}

			return ItemUseResult::FAIL;
		}

		return ItemUseResult::NONE;
	}
}
