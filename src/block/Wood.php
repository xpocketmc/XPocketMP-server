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

use pocketmine\block\utils\PillarRotationTrait;
use pocketmine\block\utils\WoodTypeTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Axe;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\sound\ItemUseOnBlockSound;

class Wood extends Opaque{
	use PillarRotationTrait;
	use WoodTypeTrait;

	private bool $stripped = false;

	public function describeBlockItemState(RuntimeDataDescriber $w) : void{
		$w->bool($this->stripped);
	}

	public function isStripped() : bool{ return $this->stripped; }

	/** @return $this */
	public function setStripped(bool $stripped) : self{
		$this->stripped = $stripped;
		return $this;
	}

	public function getFuelTime() : int{
		return $this->woodType->isFlammable() ? 300 : 0;
	}

	public function getFlameEncouragement() : int{
		return $this->woodType->isFlammable() ? 5 : 0;
	}

	public function getFlammability() : int{
		return $this->woodType->isFlammable() ? 5 : 0;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if(!$this->stripped && $item instanceof Axe){
			$item->applyDamage(1);
			$this->stripped = true;
			$this->position->getWorld()->setBlock($this->position, $this);
			$this->position->getWorld()->addSound($this->position, new ItemUseOnBlockSound($this));
			return true;
		}
		return false;
	}
}
