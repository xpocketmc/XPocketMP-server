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

namespace pocketmine\world\generator;

/**
 * Manages thread-local caches for generators and the things needed to support them
 */
final class ThreadLocalGeneratorContext{
	/**
	 * @var self[]
	 * @phpstan-var array<int, self>
	 */
	private static array $contexts = [];

	public static function register(self $context, int $worldId) : void{
		self::$contexts[$worldId] = $context;
	}

	public static function unregister(int $worldId) : void{
		unset(self::$contexts[$worldId]);
	}

	public static function fetch(int $worldId) : ?self{
		return self::$contexts[$worldId] ?? null;
	}

	public function __construct(
		private Generator $generator,
		private int $worldMinY,
		private int $worldMaxY
	){}

	public function getGenerator() : Generator{ return $this->generator; }

	public function getWorldMinY() : int{ return $this->worldMinY; }

	public function getWorldMaxY() : int{ return $this->worldMaxY; }
}
