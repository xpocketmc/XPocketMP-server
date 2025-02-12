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

namespace pocketmine\entity\attribute;

use function max;
use function min;

class AttributeValue {
	private float $currentValue;
	private bool $desynchronized = true;

	public function __construct(
		private string $id,
		private float $minValue,
		private float $maxValue,
		private float $defaultValue,
		private bool $shouldSend = true
	) {
		if ($minValue > $maxValue || $defaultValue > $maxValue || $defaultValue < $minValue) {
			throw new \InvalidArgumentException("Invalid range: min=$minValue, max=$maxValue, default=$defaultValue");
		}
		$this->currentValue = $defaultValue;
	}

	public function getId() : string {
		return $this->id;
	}

	public function isSyncable() : bool {
		return $this->shouldSend;
	}

	public function getMinValue() : float {
		return $this->minValue;
	}

	public function getMaxValue() : float {
		return $this->maxValue;
	}

	public function getDefaultValue() : float {
		return $this->defaultValue;
	}

	public function getValue() : float {
		return $this->currentValue;
	}

	public function setValue(float $value, bool $fit = false) : void {
		if ($value < $this->minValue || $value > $this->maxValue) {
			if (!$fit) {
				throw new \InvalidArgumentException("Value out of range: $value (allowed: {$this->minValue} - {$this->maxValue})");
			}
			$value = min(max($value, $this->minValue), $this->maxValue);
		}

		if ($this->currentValue !== $value) {
			$this->desynchronized = true;
			$this->currentValue = $value;
		}
	}

	public function setMinValue(float $minValue) : void {
		if ($minValue > ($max = $this->getMaxValue())) {
			throw new \InvalidArgumentException("Minimum $minValue is greater than the maximum $max");
		}

		if ($this->minValue !== $minValue) {
			$this->desynchronized = true;
			$this->minValue = $minValue;
		}
	}

	public function setMaxValue(float $maxValue) : void {
		if ($maxValue < ($min = $this->getMinValue())) {
			throw new \InvalidArgumentException("Maximum $maxValue is less than the minimum $min");
		}

		if ($this->maxValue !== $maxValue) {
			$this->desynchronized = true;
			$this->maxValue = $maxValue;
		}
	}

	public function setDefaultValue(float $defaultValue) : void{
		if($defaultValue < $this->minValue || $defaultValue > $this->maxValue){
			throw new \InvalidArgumentException("Default value must be within min and max range.");
		}
		$this->defaultValue = $defaultValue;
	}

	public function resetToDefault() : void {
		$this->setValue($this->defaultValue, true);
	}

	public function isDesynchronized() : bool {
		return $this->shouldSend && $this->desynchronized;
	}

	public function markSynchronized(bool $synced = true) : void {
		$this->desynchronized = !$synced;
	}
}
