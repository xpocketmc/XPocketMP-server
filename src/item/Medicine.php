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

namespace pocketmine\item;

use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\entity\Living;
use pocketmine\player\Player;

class Medicine extends Item implements ConsumableItem{

	private MedicineType $medicineType = MedicineType::EYE_DROPS;

	protected function describeState(RuntimeDataDescriber $w) : void{
		$w->enum($this->medicineType);
	}

	public function getType() : MedicineType{ return $this->medicineType; }

	/**
	 * @return $this
	 */
	public function setType(MedicineType $type) : self{
		$this->medicineType = $type;
		return $this;
	}

	public function getMaxStackSize() : int{
		return 1;
	}

	public function onConsume(Living $consumer) : void{
		$consumer->getEffects()->remove($this->getType()->getCuredEffect());
	}

	public function getAdditionalEffects() : array{
		return [];
	}

	public function getResidue() : Item{
		return VanillaItems::GLASS_BOTTLE();
	}

	public function canStartUsingItem(Player $player) : bool{
		return $player->getEffects()->has($this->getType()->getCuredEffect());
	}
}
