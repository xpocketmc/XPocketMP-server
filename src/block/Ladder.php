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
use XPocketMPlock\utils\SupportType;
use XPocketMP\entity\Entity;
use XPocketMP\entity\Living;
use XPocketMP\item\Item;
use XPocketMP\math\Axis;
use XPocketMP\math\AxisAlignedBB;
use XPocketMP\math\Facing;
use XPocketMP\math\Vector3;
use XPocketMP\player\Player;
use XPocketMP\world\BlockTransaction;

class Ladder extends Transparent{
	use HorizontalFacingTrait;

	public function hasEntityCollision() : bool{
		return true;
	}

	public function isSolid() : bool{
		return false;
	}

	public function canClimb() : bool{
		return true;
	}

	public function onEntityInside(Entity $entity) : bool{
		if($entity instanceof Living && $entity->getPosition()->floor()->distanceSquared($this->position) < 1){ //entity coordinates must be inside block
			$entity->resetFallDistance();
			$entity->onGround = true;
		}
		return true;
	}

	/**
	 * @return AxisAlignedBB[]
	 */
	protected function recalculateCollisionBoxes() : array{
		return [AxisAlignedBB::one()->trim($this->facing, 13 / 16)];
	}

	public function getSupportType(int $facing) : SupportType{
		return SupportType::NONE;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($this->canBeSupportedAt($blockReplace, Facing::opposite($face)) && Facing::axis($face) !== Axis::Y){
			$this->facing = $face;
			return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		}

		return false;
	}

	public function onNearbyBlockChange() : void{
		if(!$this->canBeSupportedAt($this, Facing::opposite($this->facing))){ //Replace with common break method
			$this->position->getWorld()->useBreakOn($this->position);
		}
	}

	private function canBeSupportedAt(Block $block, int $face) : bool{
		return $block->getAdjacentSupportType($face) === SupportType::FULL;
	}
}