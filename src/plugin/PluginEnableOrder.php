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

namespace pocketmine\plugin;

use pocketmine\utils\LegacyEnumShimTrait;
use function mb_strtolower;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static PluginEnableOrder POSTWORLD()
 * @method static PluginEnableOrder STARTUP()
 */
enum PluginEnableOrder{
	use LegacyEnumShimTrait;

	case STARTUP;
	case POSTWORLD;

	public static function fromString(string $name) : ?self{
		/**
		 * @var self[]|null $aliasMap
		 * @phpstan-var array<string, self>|null $aliasMap
		 */
		static $aliasMap = null;

		if($aliasMap === null){
			$aliasMap = [];
			foreach(self::cases() as $case){
				foreach($case->getAliases() as $alias){
					$aliasMap[$alias] = $case;
				}
			}
		}
		return $aliasMap[mb_strtolower($name)] ?? null;
	}

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getAliases() : array{
		return match($this){
			self::STARTUP => ["startup"],
			self::POSTWORLD => ["postworld"]
		};
	}
}
