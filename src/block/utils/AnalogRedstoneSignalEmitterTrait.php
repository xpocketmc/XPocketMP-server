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

trait AnalogRedstoneSignalEmitterTrait{
	protected int $signalStrength = 0;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->boundedIntAuto(0, 15, $this->signalStrength);
	}

	public function getOutputSignalStrength() : int{ return $this->signalStrength; }

	/** @return $this */
	public function setOutputSignalStrength(int $signalStrength) : self{
		if($signalStrength < 0 || $signalStrength > 15){
			throw new \InvalidArgumentException("Signal strength must be in range 0-15");
		}
		$this->signalStrength = $signalStrength;
		return $this;
	}
}
