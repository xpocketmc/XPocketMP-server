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
use pocketmine\entity\projectile\Projectile;
use pocketmine\math\RayTraceResult;
use pocketmine\world\sound\AmethystBlockChimeSound;
use pocketmine\world\sound\BlockPunchSound;

trait AmethystTrait{
	/**
	 * @see Block::onProjectileHit()
	 */
	public function onProjectileHit(Projectile $projectile, RayTraceResult $hitResult) : void{
		$this->position->getWorld()->addSound($this->position, new AmethystBlockChimeSound());
		$this->position->getWorld()->addSound($this->position, new BlockPunchSound($this));
	}
}
