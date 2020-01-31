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

namespace pocketmine\world\format\io;

use pocketmine\utils\Utils;
use pocketmine\world\format\io\leveldb\LevelDB;
use pocketmine\world\format\io\region\Anvil;
use pocketmine\world\format\io\region\McRegion;
use pocketmine\world\format\io\region\PMAnvil;
use function strtolower;
use function trim;

abstract class WorldProviderManager{
	/**
	 * @var string[]
	 * @phpstan-var array<string, class-string<WorldProvider>>
	 */
	protected static $providers = [];

	/**
	 * @var string
	 * @phpstan-var class-string<WritableWorldProvider>
	 */
	private static $default = LevelDB::class;

	public static function init() : void{
		self::addProvider(Anvil::class, "anvil");
		self::addProvider(McRegion::class, "mcregion");
		self::addProvider(PMAnvil::class, "pmanvil");
		self::addProvider(LevelDB::class, "leveldb");
	}

	/**
	 * Returns the default format used to generate new worlds.
	 *
	 * @phpstan-return class-string<WritableWorldProvider>
	 */
	public static function getDefault() : string{
		return self::$default;
	}

	/**
	 * Sets the default format.
	 *
	 * @param string $class Class implementing WritableWorldProvider
	 * @phpstan-param class-string<WritableWorldProvider> $class
	 *
	 * @throws \InvalidArgumentException
	 */
	public static function setDefault(string $class) : void{
		Utils::testValidInstance($class, WritableWorldProvider::class);

		self::$default = $class;
	}

	/**
	 * @phpstan-param class-string<WorldProvider> $class
	 */
	public static function addProvider(string $class, string $name, bool $overwrite = false) : void{
		Utils::testValidInstance($class, WorldProvider::class);

		$name = strtolower($name);
		if(!$overwrite and isset(self::$providers[$name])){
			throw new \InvalidArgumentException("Alias \"$name\" is already assigned");
		}

		/** @var WorldProvider $class */
		self::$providers[$name] = $class;
	}

	/**
	 * Returns a WorldProvider class for this path, or null
	 *
	 * @return string[]
	 * @phpstan-return array<string, class-string<WorldProvider>>
	 */
	public static function getMatchingProviders(string $path) : array{
		$result = [];
		foreach(self::$providers as $alias => $provider){
			if($provider::isValid($path)){
				$result[$alias] = $provider;
			}
		}
		return $result;
	}

	/**
	 * @return string[]
	 * @phpstan-return array<string, class-string<WorldProvider>>
	 */
	public static function getAvailableProviders() : array{
		return self::$providers;
	}

	/**
	 * Returns a WorldProvider by name, or null if not found
	 *
	 * @phpstan-return class-string<WorldProvider>|null
	 */
	public static function getProviderByName(string $name) : ?string{
		return self::$providers[trim(strtolower($name))] ?? null;
	}
}
