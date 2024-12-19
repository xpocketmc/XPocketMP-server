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
 * Developed: ClousClouds, PMMP Team
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Axis;
use pocketmine\math\Facing;
use pocketmine\player\Player;
use function mt_rand;

class CreakingHeart extends SimplePillar{

	protected bool $active = false;
	protected bool $natural = false;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->axis($this->axis);
		$w->bool($this->active);
		$w->bool($this->natural);
	}

	public function isActive() : bool{ return $this->active; }

	/** @return $this */
	public function setActive(bool $active) : self{
		$this->active = $active;
		return $this;
	}

	public function isNatural() : bool{ return $this->natural; }

	/** @return $this */
	public function setNatural(bool $natural) : self{
		$this->natural = $natural;
		return $this;
	}

	public function onBreak(Item $item, ?Player $player = null, array &$returnedItems = []) : bool{
		if($this->natural){
			$this->position->getWorld()->dropExperience($this->position, mt_rand(20, 24));
			return true;
		}
		return parent::onBreak($item, $player, $returnedItems);
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			VanillaBlocks::RESIN_CLUMP()->asItem()->setCount(mt_rand(1,3))
		];
	}

	public function onNearbyBlockChange() : void{
		if($this->checkActivation() && !$this->active){
			$this->active = true;
			$this->position->getWorld()->setBlock($this->position, $this);
			//TODO: Spawn Creaking entity?
		}
	}

	private function checkActivation() : bool{
		$facingPairs = $this->getFacingPairFromAxis($this->getAxis());
		if($facingPairs === null){
			return false;
		}

		[$positiveFacing, $negativeFacing] = $facingPairs;

		$positiveBlock = $this->getSide($positiveFacing);
		$negativeBlock = $this->getSide($negativeFacing);

		if($positiveBlock->getTypeId() === BlockTypeIds::PALE_OAK_LOG && $negativeBlock->getTypeId() === BlockTypeIds::PALE_OAK_LOG){
			return true;
		}

		return false;
	}

	/**
	 * @return array<int, int>|null
	 */
	private function getFacingPairFromAxis(int $axis) : ?array{
		return match($axis){
			Axis::X => [Facing::EAST, Facing::WEST],
			Axis::Y => [Facing::UP, Facing::DOWN],
			Axis::Z => [Facing::SOUTH, Facing::NORTH],
			default => null
		};
	}

	//TODO: ambient sounds
}
