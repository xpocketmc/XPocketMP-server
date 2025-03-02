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

final class InternetRequestResult{
	/**
	 * @param string[][] $headers
	 * @phpstan-param list<array<string, string>> $headers
	 */
	public function __construct(
		private array $headers,
		private string $body,
		private int $code
	){}

	/**
	 * @return string[][]
	 * @phpstan-return list<array<string, string>>
	 */
	public function getHeaders() : array{ return $this->headers; }

	public function getBody() : string{ return $this->body; }

	public function getCode() : int{ return $this->code; }
}
