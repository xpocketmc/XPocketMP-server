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

namespace pocketmine\block\utils;

use pocketmine\block\Block;
use pocketmine\entity\object\FallingBlock;
use pocketmine\world\sound\Sound;

interface Fallable{

	/**
	 * Called every tick by FallingBlock to update the falling state of this block. Used by concrete to check when it
	 * hits water.
	 * Return null if you don't want to change the usual behaviour.
	 */
	public function tickFalling() : ?Block;

	/**
	 * Called when FallingBlock hits the ground.
	 * Returns whether the block should be placed.
	 */
	public function onHitGround(FallingBlock $blockEntity) : bool;

	/**
	 * Returns the damage caused per fallen block. This is multiplied by the fall distance (and capped according to
	 * {@link Fallable::getMaxFallDamage()}) to calculate the damage dealt to any entities who intersect with the block
	 * when it hits the ground.
	 */
	public function getFallDamagePerBlock() : float;

	/**
	 * Returns the maximum damage the block can deal to an entity when it hits the ground.
	 */
	public function getMaxFallDamage() : float;

	/**
	 * Returns the sound that will be played when FallingBlock hits the ground.
	 */
	public function getLandSound() : ?Sound;
}
