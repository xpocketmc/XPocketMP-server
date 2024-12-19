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

namespace pocketmine\item\enchantment;

use pocketmine\entity\Entity;

/**
 * Classes extending this class can be applied to weapons and activate when used by a mob to attack another mob in melee
 * combat.
 */
abstract class MeleeWeaponEnchantment extends Enchantment{

	/**
	 * Returns whether this melee enchantment has an effect on the target entity. For example, Smite only applies to
	 * undead mobs.
	 */
	abstract public function isApplicableTo(Entity $victim) : bool;

	/**
	 * Returns the amount of additional damage caused by this enchantment to applicable targets.
	 */
	abstract public function getDamageBonus(int $enchantmentLevel) : float;

	/**
	 * Called after damaging the entity to apply any post damage effects to the target.
	 */
	public function onPostAttack(Entity $attacker, Entity $victim, int $enchantmentLevel) : void{

	}
}
