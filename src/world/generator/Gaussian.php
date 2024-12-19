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

namespace pocketmine\world\generator;

use function exp;

final class Gaussian{
	/** @var float[][] */
	public array $kernel = [];

	public function __construct(public int $smoothSize){
		$bellSize = 1 / $this->smoothSize;
		$bellHeight = 2 * $this->smoothSize;

		for($sx = -$this->smoothSize; $sx <= $this->smoothSize; ++$sx){
			$this->kernel[$sx + $this->smoothSize] = [];

			for($sz = -$this->smoothSize; $sz <= $this->smoothSize; ++$sz){
				$bx = $bellSize * $sx;
				$bz = $bellSize * $sz;
				$this->kernel[$sx + $this->smoothSize][$sz + $this->smoothSize] = $bellHeight * exp(-($bx * $bx + $bz * $bz) / 2);
			}
		}
	}
}
