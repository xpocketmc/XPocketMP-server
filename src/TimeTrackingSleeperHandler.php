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

namespace pocketmine;

use pocketmine\snooze\SleeperHandler;
use pocketmine\snooze\SleeperHandlerEntry;
use pocketmine\profiler\TimingsHandler;
use pocketmine\utils\Utils;
use function hrtime;

/**
 * Custom Snooze sleeper handler which captures notification processing time.
 * @internal
 */
final class TimeTrackingSleeperHandler extends SleeperHandler{

	private int $notificationProcessingTimeNs = 0;

	/**
	 * @var TimingsHandler[]
	 * @phpstan-var array<string, TimingsHandler>
	 */
	private static array $handlerTimings = [];

	public function __construct(
		private TimingsHandler $timings
	){
		parent::__construct();
	}

	public function addNotifier(\Closure $handler) : SleeperHandlerEntry{
		$name = Utils::getNiceClosureName($handler);
		$timings = self::$handlerTimings[$name] ??= new TimingsHandler("Snooze Handler: " . $name, $this->timings);

		return parent::addNotifier(function() use ($timings, $handler) : void{
			$timings->startTiming();
			$handler();
			$timings->stopTiming();
		});
	}

	/**
	 * Returns the time in nanoseconds spent processing notifications since the last reset.
	 */
	public function getNotificationProcessingTime() : int{ return $this->notificationProcessingTimeNs; }

	/**
	 * Resets the notification processing time tracker to zero.
	 */
	public function resetNotificationProcessingTime() : void{ $this->notificationProcessingTimeNs = 0; }

	public function processNotifications() : void{
		$startTime = hrtime(true);
		$this->timings->startTiming();
		try{
			parent::processNotifications();
		}finally{
			$this->notificationProcessingTimeNs += hrtime(true) - $startTime;
			$this->timings->stopTiming();
		}
	}
}
