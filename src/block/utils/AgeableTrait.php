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

namespace pocketmine\block\utils;

use pocketmine\data\runtime\RuntimeDataDescriber;

/**
 * This trait is used for blocks that have an age property.
 * Need to add to the block the constant MAX_AGE.
 */
trait AgeableTrait{
	protected int $age = 0;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->boundedIntAuto(0, self::MAX_AGE, $this->age);
	}

	public function getAge() : int{ return $this->age; }

	/**
	 * @return $this
	 */
	public function setAge(int $age) : self{
		if($age < 0 || $age > self::MAX_AGE){
			throw new \InvalidArgumentException("Age must be in range 0 ... " . self::MAX_AGE);
		}
		$this->age = $age;
		return $this;
	}
}
