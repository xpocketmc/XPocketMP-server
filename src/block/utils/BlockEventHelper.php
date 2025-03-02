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

namespace pocketmine\block\utils;

use pocketmine\block\Block;
use pocketmine\event\block\BlockDeathEvent;
use pocketmine\event\block\BlockFormEvent;
use pocketmine\event\block\BlockGrowEvent;
use pocketmine\event\block\BlockMeltEvent;
use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\player\Player;

/**
 * Helper class to call block changing events and apply the results to the world.
 * TODO: try to further reduce the amount of code duplication here - while this is much better than before, it's still
 * very repetitive.
 */
final class BlockEventHelper{

	public static function grow(Block $oldState, Block $newState, ?Player $causingPlayer) : bool{
		if(BlockGrowEvent::hasHandlers()){
			$ev = new BlockGrowEvent($oldState, $newState, $causingPlayer);
			$ev->call();
			if($ev->isCancelled()){
				return false;
			}
			$newState = $ev->getNewState();
		}

		$position = $oldState->getPosition();
		$position->getWorld()->setBlock($position, $newState);
		return true;
	}

	public static function spread(Block $oldState, Block $newState, Block $source) : bool{
		if(BlockSpreadEvent::hasHandlers()){
			$ev = new BlockSpreadEvent($oldState, $source, $newState);
			$ev->call();
			if($ev->isCancelled()){
				return false;
			}
			$newState = $ev->getNewState();
		}

		$position = $oldState->getPosition();
		$position->getWorld()->setBlock($position, $newState);
		return true;
	}

	public static function form(Block $oldState, Block $newState, Block $cause) : bool{
		if(BlockFormEvent::hasHandlers()){
			$ev = new BlockFormEvent($oldState, $newState, $cause);
			$ev->call();
			if($ev->isCancelled()){
				return false;
			}
			$newState = $ev->getNewState();
		}

		$position = $oldState->getPosition();
		$position->getWorld()->setBlock($position, $newState);
		return true;
	}

	public static function melt(Block $oldState, Block $newState) : bool{
		if(BlockMeltEvent::hasHandlers()){
			$ev = new BlockMeltEvent($oldState, $newState);
			$ev->call();
			if($ev->isCancelled()){
				return false;
			}
			$newState = $ev->getNewState();
		}

		$position = $oldState->getPosition();
		$position->getWorld()->setBlock($position, $newState);
		return true;
	}

	public static function die(Block $oldState, Block $newState) : bool{
		if(BlockDeathEvent::hasHandlers()){
			$ev = new BlockDeathEvent($oldState, $newState);
			$ev->call();
			if($ev->isCancelled()){
				return false;
			}
			$newState = $ev->getNewState();
		}

		$position = $oldState->getPosition();
		$position->getWorld()->setBlock($position, $newState);
		return true;
	}
}
