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
use XPocketMP\data\runtime\RuntimeDataDescriber;
use XPocketMP\item\Item;
use XPocketMP\item\VanillaItems;
use XPocketMP\math\Axis;
use XPocketMP\math\Facing;
use XPocketMP\math\Vector3;
use XPocketMP\player\Player;
use XPocketMP\world\BlockTransaction;

final class WallCoralFan extends BaseCoral{
	use HorizontalFacingTrait;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->horizontalFacing($this->facing);
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		$axis = Facing::axis($face);
		if(($axis !== Axis::X && $axis !== Axis::Z) || !$this->canBeSupportedAt($blockReplace, Facing::opposite($face))){
			return false;
		}
		$this->facing = $face;

		$this->dead = !$this->isCoveredWithWater();

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function onNearbyBlockChange() : void{
		$world = $this->position->getWorld();
		if(!$this->canBeSupportedAt($this, Facing::opposite($this->facing))){
			$world->useBreakOn($this->position);
		}else{
			parent::onNearbyBlockChange();
		}
	}

	private function canBeSupportedAt(Block $block, int $face) : bool{
		return $block->getAdjacentSupportType($face)->hasCenterSupport();
	}

	public function asItem() : Item{
		return VanillaItems::CORAL_FAN()->setCoralType($this->coralType)->setDead($this->dead);
	}
}