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

use pocketmine\block\tile\ItemFrame as TileItemFrame;
use pocketmine\block\utils\AnyFacingTrait;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\data\runtime\RuntimeDataWriter;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use function is_infinite;
use function is_nan;
use function lcg_value;

class ItemFrame extends Flowable{
	use AnyFacingTrait;

	public const ROTATIONS = 8;

	protected bool $glowing = false;

	protected bool $hasMap = false; //makes frame appear large if set

	protected ?Item $framedItem = null;
	protected int $itemRotation = 0;
	protected float $itemDropChance = 1.0;

	public function getRequiredTypeDataBits() : int{ return 1; }

	protected function decodeType(RuntimeDataReader $r) : void{
		$this->glowing = $r->readBool();
	}

	protected function encodeType(RuntimeDataWriter $w) : void{
		$w->writeBool($this->glowing);
	}

	public function getRequiredStateDataBits() : int{ return 4; }

	protected function decodeState(RuntimeDataReader $r) : void{
		$this->facing = $r->readFacing();
		$this->hasMap = $r->readBool();
	}

	protected function encodeState(RuntimeDataWriter $w) : void{
		$w->writeFacing($this->facing);
		$w->writeBool($this->hasMap);
	}

	public function readStateFromWorld() : void{
		parent::readStateFromWorld();
		$tile = $this->position->getWorld()->getTile($this->position);
		if($tile instanceof TileItemFrame){
			$this->framedItem = $tile->getItem();
			if($this->framedItem->isNull()){
				$this->framedItem = null;
			}
			$this->itemRotation = $tile->getItemRotation() % self::ROTATIONS;
			$this->itemDropChance = $tile->getItemDropChance();
		}
	}

	public function writeStateToWorld() : void{
		parent::writeStateToWorld();
		$tile = $this->position->getWorld()->getTile($this->position);
		if($tile instanceof TileItemFrame){
			$tile->setItem($this->framedItem);
			$tile->setItemRotation($this->itemRotation);
			$tile->setItemDropChance($this->itemDropChance);
		}
	}

	public function getFramedItem() : ?Item{
		return $this->framedItem !== null ? clone $this->framedItem : null;
	}

	/** @return $this */
	public function setFramedItem(?Item $item) : self{
		if($item === null || $item->isNull()){
			$this->framedItem = null;
			$this->itemRotation = 0;
		}else{
			$this->framedItem = clone $item;
		}
		return $this;
	}

	public function getItemRotation() : int{
		return $this->itemRotation;
	}

	/** @return $this */
	public function setItemRotation(int $itemRotation) : self{
		$this->itemRotation = $itemRotation;
		return $this;
	}

	public function getItemDropChance() : float{
		return $this->itemDropChance;
	}

	/** @return $this */
	public function setItemDropChance(float $itemDropChance) : self{
		if($itemDropChance < 0.0 || $itemDropChance > 1.0 || is_nan($itemDropChance) || is_infinite($itemDropChance)){
			throw new \InvalidArgumentException("Drop chance must be in range 0-1");
		}
		$this->itemDropChance = $itemDropChance;
		return $this;
	}

	public function hasMap() : bool{ return $this->hasMap; }

	/**
	 * This can be set irrespective of whether the frame actually contains a map or not. When set, the frame stretches
	 * to the edges of the block without leaving space around the edges.
	 *
	 * @return $this
	 */
	public function setHasMap(bool $hasMap) : self{
		$this->hasMap = $hasMap;
		return $this;
	}

	public function isGlowing() : bool{ return $this->glowing; }

	/** @return $this */
	public function setGlowing(bool $glowing) : self{
		$this->glowing = $glowing;
		return $this;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($this->framedItem !== null){
			$this->itemRotation = ($this->itemRotation + 1) % self::ROTATIONS;
		}elseif(!$item->isNull()){
			$this->framedItem = $item->pop();
		}else{
			return true;
		}

		$this->position->getWorld()->setBlock($this->position, $this);

		return true;
	}

	public function onAttack(Item $item, int $face, ?Player $player = null) : bool{
		if($this->framedItem === null){
			return false;
		}
		if(lcg_value() <= $this->itemDropChance){
			$this->position->getWorld()->dropItem($this->position->add(0.5, 0.5, 0.5), clone $this->framedItem);
		}
		$this->setFramedItem(null);
		$this->position->getWorld()->setBlock($this->position, $this);
		return true;
	}

	public function onNearbyBlockChange() : void{
		if(!$this->getSide(Facing::opposite($this->facing))->isSolid()){
			$this->position->getWorld()->useBreakOn($this->position);
		}
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if(!$blockClicked->isSolid()){
			return false;
		}

		$this->facing = $face;

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		$drops = parent::getDropsForCompatibleTool($item);
		if($this->framedItem !== null && lcg_value() <= $this->itemDropChance){
			$drops[] = clone $this->framedItem;
		}

		return $drops;
	}

	public function getPickedItem(bool $addUserData = false) : Item{
		return $this->framedItem !== null ? clone $this->framedItem : parent::getPickedItem($addUserData);
	}
}
