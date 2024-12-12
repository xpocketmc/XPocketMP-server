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
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\scheduler;

final class BulkCurlTaskOperation{
	/**
	 * @param string[] $extraHeaders
	 * @param mixed[]  $extraOpts
	 * @phpstan-param list<string> $extraHeaders
	 * @phpstan-param array<int, mixed> $extraOpts
	 */
	public function __construct(
		private string $page,
		private float $timeout = 10,
		private array $extraHeaders = [],
		private array $extraOpts = []
	){}

	public function getPage() : string{ return $this->page; }

	public function getTimeout() : float{ return $this->timeout; }

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getExtraHeaders() : array{ return $this->extraHeaders; }

	/**
	 * @return mixed[]
	 * @phpstan-return array<int, mixed>
	 */
	public function getExtraOpts() : array{ return $this->extraOpts; }
}
