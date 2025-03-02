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

namespace pocketmine\plugin;

final class PluginDescriptionCommandEntry{

	/**
	 * @param string[] $aliases
	 * @phpstan-param list<string> $aliases
	 */
	public function __construct(
		private ?string $description,
		private ?string $usageMessage,
		private array $aliases,
		private string $permission,
		private ?string $permissionDeniedMessage,
	){}

	public function getDescription() : ?string{ return $this->description; }

	public function getUsageMessage() : ?string{ return $this->usageMessage; }

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getAliases() : array{ return $this->aliases; }

	public function getPermission() : string{ return $this->permission; }

	public function getPermissionDeniedMessage() : ?string{ return $this->permissionDeniedMessage; }
}
