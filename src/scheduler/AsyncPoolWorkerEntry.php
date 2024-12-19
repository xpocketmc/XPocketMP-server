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

namespace pocketmine\scheduler;

use function time;

/**
 * @internal
 */
final class AsyncPoolWorkerEntry{

	public int $lastUsed;
	/**
	 * @var \SplQueue|AsyncTask[]
	 * @phpstan-var \SplQueue<AsyncTask>
	 */
	public \SplQueue $tasks;

	public function __construct(
		public readonly AsyncWorker $worker,
		public readonly int $sleeperNotifierId
	){
		$this->lastUsed = time();
		$this->tasks = new \SplQueue();
	}

	public function submit(AsyncTask $task) : void{
		$this->tasks->enqueue($task);
		$this->lastUsed = time();
		$this->worker->stack($task);
	}
}
