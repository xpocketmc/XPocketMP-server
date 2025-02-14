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

namespace pocketmine\utils;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see RegistryTrait::_generateMethodAnnotations()
 *
 * @method static \stdClass TEST1()
 * @method static \stdClass TEST2()
 * @method static \stdClass TEST3()
 */
final class TestCloningRegistry{
	use CloningRegistryTrait;

	/**
	 * @return \stdClass[]
	 * @phpstan-return array<string, \stdClass>
	 */
	public static function getAll() : array{
		/**
		 * @var \stdClass[] $result
		 * @phpstan-var array<string, \stdClass> $result
		 */
		$result = self::_registryGetAll();
		return $result;
	}

	public static function fromString(string $s) : \stdClass{
		/** @var \stdClass $result */
		$result = self::_registryFromString($s);
		return $result;
	}

	protected static function setup() : void{
		self::_registryRegister("test1", new \stdClass());
		self::_registryRegister("test2", new \stdClass());
		self::_registryRegister("test3", new \stdClass());
	}
}
