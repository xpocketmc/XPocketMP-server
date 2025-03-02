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

use pmmp\TesterPlugin\event\ChildEvent;
use pmmp\TesterPlugin\event\GrandchildEvent;
use pmmp\TesterPlugin\event\ParentEvent;
use pocketmine\event\EventPriority;
use function implode;

final class EventHandlerInheritanceTest extends Test{

	private const EXPECTED_ORDER = [
		GrandchildEvent::class,
		ChildEvent::class,
		ParentEvent::class
	];

	/** @var string[] */
	private array $callOrder = [];

	public function getName() : string{
		return "Event Handler Inheritance Test";
	}

	public function getDescription() : string{
		return "Tests that child events are correctly passed to parent event handlers";
	}

	public function run() : void{
		$plugin = $this->getPlugin();
		$plugin->getServer()->getPluginManager()->registerEvent(
			ParentEvent::class,
			function(ParentEvent $event) : void{
				$this->callOrder[] = ParentEvent::class;
			},
			EventPriority::NORMAL,
			$plugin
		);
		$plugin->getServer()->getPluginManager()->registerEvent(
			ChildEvent::class,
			function(ChildEvent $event) : void{
				$this->callOrder[] = ChildEvent::class;
			},
			EventPriority::NORMAL,
			$plugin
		);
		$plugin->getServer()->getPluginManager()->registerEvent(
			GrandchildEvent::class,
			function(GrandchildEvent $event) : void{
				$this->callOrder[] = GrandchildEvent::class;
			},
			EventPriority::NORMAL,
			$plugin
		);

		$event = new GrandchildEvent();
		$event->call();

		if($this->callOrder === self::EXPECTED_ORDER){
			$this->setResult(Test::RESULT_OK);
		}else{
			$plugin->getLogger()->error("Expected order: " . implode(", ", self::EXPECTED_ORDER) . ", got: " . implode(", ", $this->callOrder));
			$this->setResult(Test::RESULT_FAILED);
		}
	}
}
