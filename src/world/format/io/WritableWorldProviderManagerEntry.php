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

namespace pocketmine\world\format\io;

use pocketmine\world\WorldCreationOptions;

/**
 * @phpstan-type FromPath \Closure(string $path, \Logger $logger) : WritableWorldProvider
 * @phpstan-type Generate \Closure(string $path, string $name, WorldCreationOptions $options) : void
 */
final class WritableWorldProviderManagerEntry extends WorldProviderManagerEntry{

	/**
	 * @phpstan-param FromPath $fromPath
	 * @phpstan-param Generate $generate
	 */
	public function __construct(
		\Closure $isValid,
		private \Closure $fromPath,
		private \Closure $generate
	){
		parent::__construct($isValid);
	}

	public function fromPath(string $path, \Logger $logger) : WritableWorldProvider{
		return ($this->fromPath)($path, $logger);
	}

	/**
	 * Generates world manifest files and any other things needed to initialize a new world on disk
	 */
	public function generate(string $path, string $name, WorldCreationOptions $options) : void{
		($this->generate)($path, $name, $options);
	}
}
