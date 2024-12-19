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

namespace pocketmine\event\entity;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\utils\Utils;
use pocketmine\world\Position;

/**
 * Called when an entity explodes, after the explosion's impact has been calculated.
 * No changes have been made to the world at this stage.
 *
 * @see EntityPreExplodeEvent
 *
 * @phpstan-extends EntityEvent<Entity>
 */
class EntityExplodeEvent extends EntityEvent implements Cancellable{
	use CancellableTrait;

	/**
	 * @param Block[] $blocks
	 * @param float   $yield  0-100
	 */
	public function __construct(
		Entity $entity,
		protected Position $position,
		protected array $blocks,
		protected float $yield
	){
		$this->entity = $entity;
		if($yield < 0.0 || $yield > 100.0){
			throw new \InvalidArgumentException("Yield must be in range 0.0 - 100.0");
		}
	}

	public function getPosition() : Position{
		return $this->position;
	}

	/**
	 * Returns a list of blocks destroyed by the explosion.
	 *
	 * @return Block[]
	 */
	public function getBlockList() : array{
		return $this->blocks;
	}

	/**
	 * Sets the blocks destroyed by the explosion.
	 *
	 * @param Block[] $blocks
	 */
	public function setBlockList(array $blocks) : void{
		Utils::validateArrayValueType($blocks, function(Block $_) : void{});
		$this->blocks = $blocks;
	}

	/**
	 * Returns the percentage chance of drops from each block destroyed by the explosion.
	 * @return float 0-100
	 */
	public function getYield() : float{
		return $this->yield;
	}

	/**
	 * Sets the percentage chance of drops from each block destroyed by the explosion.
	 * @param float $yield 0-100
	 */
	public function setYield(float $yield) : void{
		if($yield < 0.0 || $yield > 100.0){
			throw new \InvalidArgumentException("Yield must be in range 0.0 - 100.0");
		}
		$this->yield = $yield;
	}
}
