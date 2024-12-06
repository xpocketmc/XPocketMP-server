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

use XPocketMPlock\Block;
use XPocketMP\event\block\BlockDeathEvent;
use XPocketMP\event\block\BlockFormEvent;
use XPocketMP\event\block\BlockGrowEvent;
use XPocketMP\event\block\BlockMeltEvent;
use XPocketMP\event\block\BlockSpreadEvent;
use XPocketMP\player\Player;

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