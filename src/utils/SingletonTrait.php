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

namespace pocketmine\utils;

trait SingletonTrait{
	/** @var self|null */
	private static $instance = null;

	private static function make() : self{
		return new self();
	}

	public static function getInstance() : self{
		if(self::$instance === null){
			self::$instance = self::make();
		}
		return self::$instance;
	}

	public static function setInstance(self $instance) : void{
		self::$instance = $instance;
	}

	public static function reset() : void{
		self::$instance = null;
	}
}
