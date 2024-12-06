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

namespace XPocketMPlock;

use XPocketMPlock\utils\HorizontalFacingTrait;
use XPocketMPlock\utils\PoweredByRedstoneTrait;
use XPocketMPlock\utils\StaticSupportTrait;
use XPocketMPlock\utils\SupportType;
use XPocketMP\data\runtime\RuntimeDataDescriber;
use XPocketMP\item\Item;
use XPocketMP\math\AxisAlignedBB;
use XPocketMP\math\Facing;
use XPocketMP\math\Vector3;
use XPocketMP\player\Player;
use XPocketMP\world\BlockTransaction;

class RedstoneRepeater extends Flowable{
	use HorizontalFacingTrait;
	use PoweredByRedstoneTrait;
	use StaticSupportTrait;

	public const MIN_DELAY = 1;
	public const MAX_DELAY = 4;

	protected int $delay = self::MIN_DELAY;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->horizontalFacing($this->facing);
		$w->boundedIntAuto(self::MIN_DELAY, self::MAX_DELAY, $this->delay);
		$w->bool($this->powered);
	}

	public function getDelay() : int{ return $this->delay; }

	/** @return $this */
	public function setDelay(int $delay) : self{
		if($delay < self::MIN_DELAY || $delay > self::MAX_DELAY){
			throw new \InvalidArgumentException("Delay must be in range " . self::MIN_DELAY . " ... " . self::MAX_DELAY);
		}
		$this->delay = $delay;
		return $this;
	}

	/**
	 * @return AxisAlignedBB[]
	 */
	protected function recalculateCollisionBoxes() : array{
		return [AxisAlignedBB::one()->trim(Facing::UP, 7 / 8)];
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($player !== null){
			$this->facing = Facing::opposite($player->getHorizontalFacing());
		}

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if(++$this->delay > self::MAX_DELAY){
			$this->delay = self::MIN_DELAY;
		}
		$this->position->getWorld()->setBlock($this->position, $this);
		return true;
	}

	private function canBeSupportedAt(Block $block) : bool{
		return $block->getAdjacentSupportType(Facing::DOWN) !== SupportType::NONE;
	}

	//TODO: redstone functionality
}