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

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;

/**
 * Called when an entity takes damage from an entity sourced from another entity, for example being hit by a snowball thrown by a Player.
 */
class EntityDamageByChildEntityEvent extends EntityDamageByEntityEvent{
	private int $childEntityEid;

	/**
	 * @param float[] $modifiers
	 */
	public function __construct(Entity $damager, Entity $childEntity, Entity $entity, int $cause, float $damage, array $modifiers = []){
		$this->childEntityEid = $childEntity->getId();
		parent::__construct($damager, $entity, $cause, $damage, $modifiers);
	}

	/**
	 * Returns the entity which caused the damage, or null if the entity has been killed or closed.
	 */
	public function getChild() : ?Entity{
		return $this->getEntity()->getWorld()->getServer()->getWorldManager()->findEntity($this->childEntityEid);
	}
}
