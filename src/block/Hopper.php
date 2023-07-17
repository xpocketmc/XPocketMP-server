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

use pocketmine\block\tile\Hopper as TileHopper;
use pocketmine\block\tile\Furnace as TileFurnance;
use pocketmine\block\utils\PoweredByRedstoneTrait;
use pocketmine\block\utils\SupportType;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use pocketmine\item\ItemTypeIds;

class Hopper extends Transparent{
	use PoweredByRedstoneTrait;

	private int $facing = Facing::DOWN;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->facingExcept($this->facing, Facing::UP);
		$w->bool($this->powered);
	}

	public function getFacing() : int{ return $this->facing; }

	/** @return $this */
	public function setFacing(int $facing) : self{
		if($facing === Facing::UP){
			throw new \InvalidArgumentException("Hopper may not face upward");
		}
		$this->facing = $facing;
		return $this;
	}

	protected function recalculateCollisionBoxes() : array{
		$result = [
			AxisAlignedBB::one()->trim(Facing::UP, 6 / 16) //the empty area around the bottom is currently considered solid
		];

		foreach(Facing::HORIZONTAL as $f){ //add the frame parts around the bowl
			$result[] = AxisAlignedBB::one()->trim($f, 14 / 16);
		}
		return $result;
	}

	public function getSupportType(int $facing) : SupportType{
		return match($facing){
			Facing::UP => SupportType::FULL(),
			Facing::DOWN => $this->facing === Facing::DOWN ? SupportType::CENTER() : SupportType::NONE(),
			default => SupportType::NONE()
		};
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		$this->facing = $face === Facing::DOWN ? Facing::DOWN : Facing::opposite($face);

		$world = $this->position->getWorld();
		$world->scheduleDelayedBlockUpdate($blockReplace->position, 8);

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($player !== null){
			$tile = $this->position->getWorld()->getTile($this->position);
			if($tile instanceof TileHopper){ //TODO: find a way to have inventories open on click without this boilerplate in every block
				$player->setCurrentWindow($tile->getInventory());
			}
			return true;
		}
		return false;
	}

	public function onScheduledUpdate() : void{
		$world = $this->position->getWorld();
		$this->push();
		$world->scheduleDelayedBlockUpdate($this->position, 8);
	}

	public function push(){
		$tile = $this->position->getWorld()->getTile($this->position);
		if(!$tile instanceof TileHopper) return;

		$facingBlock = $this->getSide($this->facing);

		match($facingBlock::class){
			Hopper::class => $this->transferToHopper($tile, $facingBlock),
			Furnace::class => $this->transferToFurnance($tile, $facingBlock),
			default => null
		};
	}

	private function transferToHopper(TileHopper $tileHopper, Hopper $facingBlock) : void{
		$facingTile = $this->position->getWorld()->getTile($facingBlock->position);
		if(!$facingTile instanceof TileHopper) return;

		$sourceInventory = $tileHopper->getInventory();
		$targetInventory = $facingTile->getInventory();

		for($i = 0; $i < 5; $i++){
			$itemStack = $sourceInventory->getItem($i);

			if($itemStack->isNull()) continue;

			$singleItem = $itemStack->pop(1);

			if($targetInventory->canAddItem($singleItem)){
				$this->transferItem($sourceInventory, $targetInventory, $singleItem);
			}

			break;
		}
	}

	private function transferToFurnance(TileHopper $tileHopper, Furnace $facingBlock) : void{
		$facingTile = $this->position->getWorld()->getTile($facingBlock->position);
		if(!$facingTile instanceof TileFurnance) return;

		$hopperFacing = $tileHopper->getBlock()->getFacing();
		$inventory = $tileHopper->getInventory();
		$furnanceInventory = $facingTile->getInventory();

		for($i = 0; $i < 5; $i++){
			$itemStack = $inventory->getItem($i);
	
			if($itemStack->isNull()) continue;
	
			$singleItem = $itemStack->pop(1);
			$typeId = $singleItem->getTypeId();

			if($hopperFacing === Facing::DOWN && $furnanceInventory->canAddSmelting($singleItem)){
				$this->transferItem($inventory, $furnanceInventory, $singleItem, 0);
			}else if($hopperFacing !== Facing::DOWN && $hopperFacing !== Facing::UP && $furnanceInventory->canAddFuel($singleItem)){
				$this->transferItem($inventory, $furnanceInventory, $singleItem, 1);
			}
		}
	}

	private function transferItem($sourceInventory, $targetInventory, Item $item, int $slot = null) : void{
		$sourceInventory->removeItem($item);

		if($slot === null){
			$sourceInventory->removeItem($item);
			$targetInventory->addItem($item);
			return;
		}

		$currentItem = $targetInventory->getItem($slot);

		if($currentItem->isNull()){
			$targetInventory->setItem($slot, $item);
			return;
		}

		$currentItem->setCount($currentItem->getCount() + 1);
		$targetInventory->setItem($slot, $currentItem);
	}

	//TODO: redstone logic, sucking logic
}
