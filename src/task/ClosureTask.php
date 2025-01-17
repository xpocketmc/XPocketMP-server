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

namespace pocketmine\task;

use DaveRandom\CallbackValidator\CallbackType;
use DaveRandom\CallbackValidator\ReturnType;
use pocketmine\utils\Utils;

/**
 * Task implementation which allows closures to be called by a scheduler.
 *
 * Example usage:
 *
 * ```
 * TaskScheduler->scheduleTask(new ClosureTask(function() : void{
 *     echo "HI\n";
 * });
 * ```
 */
class ClosureTask extends Task{
	/**
	 * @param \Closure $closure Must accept zero parameters
	 * @phpstan-param \Closure() : void $closure
	 */
	public function __construct(
		private \Closure $closure
	){
		Utils::validateCallableSignature(new CallbackType(new ReturnType()), $closure);
	}

	public function getName() : string{
		return Utils::getNiceClosureName($this->closure);
	}

	public function onRun() : void{
		($this->closure)();
	}
}
