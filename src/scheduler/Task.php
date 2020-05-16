<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\scheduler;

use pocketmine\utils\Utils;

abstract class Task{

	/** @var TaskHandler|null */
	private $taskHandler = null;

	/**
	 * @return TaskHandler|null
	 */
	final public function getHandler(){
		return $this->taskHandler;
	}

	final public function getTaskId() : int{
		if($this->taskHandler !== null){
			return $this->taskHandler->getTaskId();
		}

		return -1;
	}

	public function getName() : string{
		return Utils::getNiceClassName($this);
	}

	final public function setHandler(?TaskHandler $taskHandler) : void{
		if($this->taskHandler === null or $taskHandler === null){
			$this->taskHandler = $taskHandler;
		}
	}

	/**
	 * Actions to execute when run
	 *
	 * @return void
	 */
	abstract public function onRun();

	/**
	 * Actions to execute if the Task is cancelled
	 *
	 * @return void
	 */
	public function onCancel(){

	}
}
