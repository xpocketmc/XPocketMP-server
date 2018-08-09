<?php

/*
 *               _ _
 *         /\   | | |
 *        /  \  | | |_ __ _ _   _
 *       / /\ \ | | __/ _` | | | |
 *      / ____ \| | || (_| | |_| |
 *     /_/    \_|_|\__\__,_|\__, |
 *                           __/ |
 *                          |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TuranicTeam
 * @link https://github.com/TuranicTeam/Altay
 *
 */

declare(strict_types=1);

namespace pocketmine\event\server;

use pocketmine\utils\Utils;


/**
 * Called when the server is in a low-memory state as defined by the properties
 * Plugins should free caches or other non-essential data.
 */
class LowMemoryEvent extends ServerEvent{
	/** @var int */
	private $memory;
	/** @var int */
	private $memoryLimit;
	/** @var int */
	private $triggerCount;
	/** @var bool */
	private $global;

	public function __construct(int $memory, int $memoryLimit, bool $isGlobal = false, int $triggerCount = 0){
		$this->memory = $memory;
		$this->memoryLimit = $memoryLimit;
		$this->global = $isGlobal;
		$this->triggerCount = $triggerCount;
	}

	/**
	 * Returns the memory usage at the time of the event call (in bytes)
	 *
	 * @return int
	 */
	public function getMemory() : int{
		return $this->memory;
	}

	/**
	 * Returns the memory limit defined (in bytes)
	 *
	 * @return int
	 */
	public function getMemoryLimit() : int{
		return $this->memoryLimit;
	}

	/**
	 * Returns the times this event has been called in the current low-memory state
	 *
	 * @return int
	 */
	public function getTriggerCount() : int{
		return $this->triggerCount;
	}

	/**
	 * @return bool
	 */
	public function isGlobal() : bool{
		return $this->global;
	}

	/**
	 * Amount of memory already freed
	 *
	 * @return int
	 */
	public function getMemoryFreed() : int{
		return $this->getMemory() - ($this->isGlobal() ? Utils::getMemoryUsage(true)[1] : Utils::getMemoryUsage(true)[0]);
	}

}