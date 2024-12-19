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

namespace pocketmine\world\light;

final class LightPropagationContext{

	/** @phpstan-var \SplQueue<array{int, int, int}> */
	public \SplQueue $spreadQueue;
	/**
	 * @var int[]|true[]
	 * @phpstan-var array<int, int|true>
	 */
	public array $spreadVisited = [];

	/** @phpstan-var \SplQueue<array{int, int, int, int}> */
	public \SplQueue $removalQueue;
	/**
	 * @var true[]
	 * @phpstan-var array<int, true>
	 */
	public array $removalVisited = [];

	public function __construct(){
		$this->removalQueue = new \SplQueue();
		$this->spreadQueue = new \SplQueue();
	}
}
