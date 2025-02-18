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

namespace pmmp\TesterPlugin;

use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\ClosureTask;
use function array_shift;

class Main extends PluginBase implements Listener{

	/** @var Test[] */
	protected $waitingTests = [];
	/** @var Test|null */
	protected $currentTest = null;
	/** @var Test[] */
	protected $completedTests = [];
	/** @var int */
	protected $currentTestNumber = 0;

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function() : void{
			if($this->currentTest === null){
				if(!$this->startNextTest()){
					$this->onAllTestsCompleted();
					throw new CancelTaskException();
				}
			}elseif($this->currentTest->isFinished() || $this->currentTest->isTimedOut()){
				$this->onTestCompleted($this->currentTest);
			}else{
				$this->currentTest->tick();
			}
		}), 10);

		$this->waitingTests = [
			new EventHandlerInheritanceTest($this),
		];
	}

	public function onServerCommand(CommandEvent $event) : void{
		//The CI will send this command as a failsafe to prevent the build from hanging if the tester plugin failed to
		//run. However, if the plugin loaded successfully we don't want to allow this to stop the server as there may
		//be asynchronous tests running. Instead we cancel this and stop the server of our own accord once all tests
		//have completed.
		if($event->getCommand() === "stop"){
			$event->cancel();
		}
	}

	private function startNextTest() : bool{
		$this->currentTest = array_shift($this->waitingTests);
		if($this->currentTest !== null){
			$this->getLogger()->notice("Running test #" . (++$this->currentTestNumber) . " (" . $this->currentTest->getName() . ")");
			$this->currentTest->start();
			return true;
		}

		return false;
	}

	private function onTestCompleted(Test $test) : void{
		$message = "Finished test #" . $this->currentTestNumber . " (" . $test->getName() . "): ";
		switch($test->getResult()){
			case Test::RESULT_OK:
				$message .= "PASS";
				break;
			case Test::RESULT_FAILED:
				$message .= "FAIL";
				break;
			case Test::RESULT_ERROR:
				$message .= "ERROR";
				break;
			case Test::RESULT_WAITING:
				$message .= "TIMEOUT";
				break;
			default:
				$message .= "UNKNOWN";
				break;
		}

		$this->getLogger()->notice($message);

		$this->completedTests[$this->currentTestNumber] = $test;
		$this->currentTest = null;
	}

	private function onAllTestsCompleted() : void{
		$this->getLogger()->notice("All tests finished, stopping the server");
		$this->getServer()->shutdown();
	}
}
