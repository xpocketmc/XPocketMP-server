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

use pocketmine\world\format\io\exception\CorruptedWorldException;
use pocketmine\world\format\io\exception\UnsupportedWorldFormatException;

/**
 * @phpstan-type IsValid \Closure(string $path) : bool
 */
abstract class WorldProviderManagerEntry{

	/** @phpstan-param IsValid $isValid */
	protected function __construct(
		protected \Closure $isValid
	){}

	/**
	 * Tells if the path is a valid world.
	 * This must tell if the current format supports opening the files in the directory
	 */
	public function isValid(string $path) : bool{ return ($this->isValid)($path); }

	/**
	 * @throws CorruptedWorldException
	 * @throws UnsupportedWorldFormatException
	 */
	abstract public function fromPath(string $path, \Logger $logger) : WorldProvider;
}
