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

use pocketmine\block\utils\AnalogRedstoneSignalEmitterTrait;
use function ceil;
use function count;
use function max;
use function min;

class WeightedPressurePlate extends PressurePlate{
	use AnalogRedstoneSignalEmitterTrait;

	private readonly float $signalStrengthFactor;

	/**
	 * @param float $signalStrengthFactor Number of entities on the plate is divided by this value to get signal strength
	 */
	public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo, int $deactivationDelayTicks, float $signalStrengthFactor = 1.0){
		parent::__construct($idInfo, $name, $typeInfo, $deactivationDelayTicks);
		$this->signalStrengthFactor = $signalStrengthFactor;
	}

	protected function hasOutputSignal() : bool{
		return $this->signalStrength > 0;
	}

	protected function calculatePlateState(array $entities) : array{
		$newSignalStrength = min(15, max(0,
			(int) ceil(count($entities) * $this->signalStrengthFactor)
		));
		if($newSignalStrength === $this->signalStrength){
			return [$this, null];
		}
		$wasActive = $this->signalStrength !== 0;
		$isActive = $newSignalStrength !== 0;
		return [
			(clone $this)->setOutputSignalStrength($newSignalStrength),
			$wasActive !== $isActive ? $isActive : null
		];
	}
}
