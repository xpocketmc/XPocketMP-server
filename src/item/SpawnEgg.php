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

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\World;
use function lcg_value;

abstract class SpawnEgg extends Item{

	abstract protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity;

	public function onInteractBlock(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, array &$returnedItems) : ItemUseResult{
		$entity = $this->createEntity($player->getWorld(), $blockReplace->getPosition()->add(0.5, 0, 0.5), lcg_value() * 360, 0);

		if($this->hasCustomName()){
			$entity->setNameTag($this->getCustomName());
		}
		$this->pop();
		$entity->spawnToAll();
		//TODO: what if the entity was marked for deletion?
		return ItemUseResult::SUCCESS;
	}
}
