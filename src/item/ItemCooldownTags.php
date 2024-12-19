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

namespace pocketmine\item;

/**
 * Tags used by items to determine their cooldown group.
 *
 * These tag values are not related to Minecraft internal IDs.
 * They only share a visual similarity because these are the most obvious values to use.
 * Any arbitrary string can be used.
 *
 * @see Item::getCooldownTag()
 */
final class ItemCooldownTags{

	private function __construct(){
		//NOOP
	}

	public const CHORUS_FRUIT = "chorus_fruit";
	public const ENDER_PEARL = "ender_pearl";
	public const SHIELD = "shield";
	public const GOAT_HORN = "goat_horn";
}
