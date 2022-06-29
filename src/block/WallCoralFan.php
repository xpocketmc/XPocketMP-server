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
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\BlockDataReader;
use pocketmine\block\utils\BlockDataWriter;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\data\bedrock\CoralTypeIdMap;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\math\Axis;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

final class WallCoralFan extends BaseCoral{
	use HorizontalFacingTrait;

	public function getLegacyItemId() : int{
		return $this->dead ? ItemIds::CORAL_FAN_DEAD : ItemIds::CORAL_FAN;
	}

	protected function writeStateToItemMeta() : int{
		return CoralTypeIdMap::getInstance()->toId($this->coralType);
	}

	public function getRequiredStateDataBits() : int{ return parent::getRequiredStateDataBits() + 2; }

	protected function decodeState(BlockDataReader $r) : void{
		parent::decodeState($r);
		$this->facing = $r->readHorizontalFacing();
	}

	protected function encodeState(BlockDataWriter $w) : void{
		parent::encodeState($w);
		$w->writeHorizontalFacing($this->facing);
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		$axis = Facing::axis($face);
		if(($axis !== Axis::X && $axis !== Axis::Z) || !$this->canBeSupportedBy($blockClicked, $face)){
			return false;
		}
		$this->facing = $face;
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function onNearbyBlockChange() : void{
		$world = $this->position->getWorld();
		if(!$this->canBeSupportedBy($world->getBlock($this->position->getSide(Facing::opposite($this->facing))), $this->facing)){
			$world->useBreakOn($this->position);
		}else{
			parent::onNearbyBlockChange();
		}
	}

	private function canBeSupportedBy(Block $block, int $face) : bool{
		return $block->getSupportType($face)->hasCenterSupport();
	}
}
