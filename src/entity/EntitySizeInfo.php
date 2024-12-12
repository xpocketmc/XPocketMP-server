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

namespace pocketmine\entity;

use function min;

final class EntitySizeInfo{
	private float $eyeHeight;

	public function __construct(
		private float $height,
		private float $width,
		?float $eyeHeight = null
	){
		$this->eyeHeight = $eyeHeight ?? min($this->height / 2 + 0.1, $this->height);
	}

	public function getHeight() : float{ return $this->height; }

	public function getWidth() : float{ return $this->width; }

	public function getEyeHeight() : float{ return $this->eyeHeight; }

	public function scale(float $newScale) : self{
		return new self(
			$this->height * $newScale,
			$this->width * $newScale,
			$this->eyeHeight * $newScale
		);
	}
}
