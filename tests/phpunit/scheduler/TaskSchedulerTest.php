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

namespace pocketmine\scheduler;

use PHPUnit\Framework\TestCase;

class TaskSchedulerTest extends TestCase{

	/** @var TaskScheduler */
	private $scheduler;

	public function setUp() : void{
		$this->scheduler = new TaskScheduler();
	}

	public function tearDown() : void{
		$this->scheduler->shutdown();
	}

	public function testCancel() : void{
		$task = $this->scheduler->scheduleTask(new ClosureTask(function() : void{
			throw new CancelTaskException();
		}));
		$this->scheduler->mainThreadHeartbeat(0);
		self::assertTrue($task->isCancelled(), "Task was not cancelled");
	}
}
