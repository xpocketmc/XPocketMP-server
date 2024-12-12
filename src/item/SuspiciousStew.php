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
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\data\runtime\RuntimeDataDescriber;

class SuspiciousStew extends Food{

	private SuspiciousStewType $suspiciousStewType = SuspiciousStewType::POPPY;

	protected function describeState(RuntimeDataDescriber $w) : void{
		$w->enum($this->suspiciousStewType);
	}

	public function getType() : SuspiciousStewType{ return $this->suspiciousStewType; }

	/**
	 * @return $this
	 */
	public function setType(SuspiciousStewType $type) : self{
		$this->suspiciousStewType = $type;
		return $this;
	}

	public function getMaxStackSize() : int{
		return 1;
	}

	public function requiresHunger() : bool{
		return false;
	}

	public function getFoodRestore() : int{
		return 6;
	}

	public function getSaturationRestore() : float{
		return 7.2;
	}

	public function getAdditionalEffects() : array{
		return $this->suspiciousStewType->getEffects();
	}

	public function getResidue() : Item{
		return VanillaItems::BOWL();
	}
}
