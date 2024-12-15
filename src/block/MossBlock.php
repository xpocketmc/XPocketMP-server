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

namespace pocketmine\block;

use pocketmine\block\utils\MossBlockType;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\item\PotionType;
use pocketmine\item\SplashPotion;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\sound\WaterSplashSound;

class MossBlock extends Opaque{
	protected MossBlockType $mossType = MossBlockType::NORMAL;

	public function describeBlockItemState(RuntimeDataDescriber $w) : void{
		$w->enum($this->mossType);
	}

	public function getMossType() : MossBlockType{
		return $this->mossType;
	}

	public function setMossType(MossBlockType $mossType) : self{
		$this->mossType = $mossType;
		return $this;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		$world = $this->position->getWorld();

		if($item instanceof Fertilizer){
			if($this->mossType === MossBlockType::NORMAL){
				$item->pop();
				$world->setBlock($this->position, VanillaBlocks::GRASS());
				return true;
			}
		}elseif(($item instanceof Potion || $item instanceof SplashPotion) && $item->getType() === PotionType::WATER){
			if($this->mossType === MossBlockType::PALE){
				$item->pop();
				$this->mossType = MossBlockType::NORMAL;
				$world->addSound($this->position, new WaterSplashSound(0.5));
				return true;
			}
		}

		return false;
	}
}
