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

namespace pocketmine\world\format\io;

use pocketmine\utils\Utils;
use pocketmine\world\format\io\leveldb\LevelDB;
use pocketmine\world\format\io\region\Anvil;
use pocketmine\world\format\io\region\McRegion;
use pocketmine\world\format\io\region\PMAnvil;
use function strtolower;
use function trim;

final class WorldProviderManager{
	/**
	 * @var WorldProviderManagerEntry[]
	 * @phpstan-var array<string, WorldProviderManagerEntry>
	 */
	protected array $providers = [];

	private WritableWorldProviderManagerEntry $default;

	public function __construct(){
		$leveldb = new WritableWorldProviderManagerEntry(LevelDB::isValid(...), fn(string $path, \Logger $logger) => new LevelDB($path, $logger), LevelDB::generate(...));
		$this->default = $leveldb;
		$this->addProvider($leveldb, "leveldb");

		$this->addProvider(new ReadOnlyWorldProviderManagerEntry(Anvil::isValid(...), fn(string $path, \Logger $logger) => new Anvil($path, $logger)), "anvil");
		$this->addProvider(new ReadOnlyWorldProviderManagerEntry(McRegion::isValid(...), fn(string $path, \Logger $logger) => new McRegion($path, $logger)), "mcregion");
		$this->addProvider(new ReadOnlyWorldProviderManagerEntry(PMAnvil::isValid(...), fn(string $path, \Logger $logger) => new PMAnvil($path, $logger)), "pmanvil");
	}

	/**
	 * Returns the default format used to generate new worlds.
	 */
	public function getDefault() : WritableWorldProviderManagerEntry{
		return $this->default;
	}

	public function setDefault(WritableWorldProviderManagerEntry $class) : void{
		$this->default = $class;
	}

	public function addProvider(WorldProviderManagerEntry $providerEntry, string $name, bool $overwrite = false) : void{
		$name = strtolower($name);
		if(!$overwrite && isset($this->providers[$name])){
			throw new \InvalidArgumentException("Alias \"$name\" is already assigned");
		}

		$this->providers[$name] = $providerEntry;
	}

	/**
	 * Returns a WorldProvider class for this path, or null
	 *
	 * @return WorldProviderManagerEntry[]
	 * @phpstan-return array<string, WorldProviderManagerEntry>
	 */
	public function getMatchingProviders(string $path) : array{
		$result = [];
		foreach(Utils::stringifyKeys($this->providers) as $alias => $providerEntry){
			if($providerEntry->isValid($path)){
				$result[$alias] = $providerEntry;
			}
		}
		return $result;
	}

	/**
	 * @return WorldProviderManagerEntry[]
	 * @phpstan-return array<string, WorldProviderManagerEntry>
	 */
	public function getAvailableProviders() : array{
		return $this->providers;
	}

	/**
	 * Returns a WorldProvider by name, or null if not found
	 */
	public function getProviderByName(string $name) : ?WorldProviderManagerEntry{
		return $this->providers[trim(strtolower($name))] ?? null;
	}
}
