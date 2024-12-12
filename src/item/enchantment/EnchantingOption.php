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

namespace pocketmine\item\enchantment;

/**
 * Represents an option on the enchanting table menu.
 * If selected, all the enchantments in the option will be applied to the item.
 */
class EnchantingOption{

	/**
	 * @param EnchantmentInstance[] $enchantments
	 */
	public function __construct(
		private int $requiredXpLevel,
		private string $displayName,
		private array $enchantments
	){}

	/**
	 * Returns the minimum amount of XP levels required to select this enchantment option.
	 * It's NOT the number of XP levels that will be subtracted after enchanting.
	 */
	public function getRequiredXpLevel() : int{
		return $this->requiredXpLevel;
	}

	/**
	 * Returns the name that will be translated to the 'Standard Galactic Alphabet' client-side.
	 * This can be any arbitrary text string, since the vanilla client cannot read the text anyway.
	 * Example: 'bless creature range free'.
	 */
	public function getDisplayName() : string{
		return $this->displayName;
	}

	/**
	 * Returns the enchantments that will be applied to the item when this option is clicked.
	 *
	 * @return EnchantmentInstance[]
	 */
	public function getEnchantments() : array{
		return $this->enchantments;
	}
}
