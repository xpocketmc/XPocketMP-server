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

namespace pocketmine\block;

use pocketmine\block\utils\SupportType;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\Axis;
use pocketmine\math\AxisAlignedBB;

class NetherPortal extends Transparent{

	protected int $axis = Axis::X;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->horizontalAxis($this->axis);
	}

	public function getAxis() : int{
		return $this->axis;
	}

	/**
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function setAxis(int $axis) : self{
		if($axis !== Axis::X && $axis !== Axis::Z){
			throw new \InvalidArgumentException("Invalid axis");
		}
		$this->axis = $axis;
		return $this;
	}

	public function getLightLevel() : int{
		return 11;
	}

	public function isSolid() : bool{
		return false;
	}

	/**
	 * @return AxisAlignedBB[]
	 */
	protected function recalculateCollisionBoxes() : array{
		return [];
	}

	public function getSupportType(int $facing) : SupportType{
		return SupportType::NONE;
	}

	public function getDrops(Item $item) : array{
		return [];
	}

	public function onEntityInside(Entity $entity) : bool{
		//TODO
		return true;
	}
}
