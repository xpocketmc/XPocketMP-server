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
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\world\generator;

final class GeneratorManagerEntry{

	/**
	 * @phpstan-param class-string<Generator> $generatorClass
	 * @phpstan-param \Closure(string) : ?InvalidGeneratorOptionsException $presetValidator
	 */
	public function __construct(
		private string $generatorClass,
		private \Closure $presetValidator
	){}

	/** @phpstan-return class-string<Generator> */
	public function getGeneratorClass() : string{ return $this->generatorClass; }

	/**
	 * @throws InvalidGeneratorOptionsException
	 */
	public function validateGeneratorOptions(string $generatorOptions) : void{
		if(($exception = ($this->presetValidator)($generatorOptions)) !== null){
			throw $exception;
		}
	}
}
