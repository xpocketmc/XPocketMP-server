<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author XPocketMP Team
 * @link http://www.xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace XPocketMPlock;

use XPocketMPlock\tile\Furnace as TileFurnace;
use XPocketMPlock\utils\FacesOppositePlacingPlayerTrait;
use XPocketMPlock\utils\LightableTrait;
use XPocketMP\crafting\FurnaceType;
use XPocketMP\data\runtime\RuntimeDataDescriber;
use XPocketMP\item\Item;
use XPocketMP\math\Vector3;
use XPocketMP\player\Player;
use function mt_rand;

class Furnace extends Opaque{
	use FacesOppositePlacingPlayerTrait;
	use LightableTrait;

	protected FurnaceType $furnaceType;

	public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo, FurnaceType $furnaceType){
		$this->furnaceType = $furnaceType;
		parent::__construct($idInfo, $name, $typeInfo);
	}

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->horizontalFacing($this->facing);
		$w->bool($this->lit);
	}

	public function getFurnaceType() : FurnaceType{
		return $this->furnaceType;
	}

	public function getLightLevel() : int{
		return $this->lit ? 13 : 0;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($player instanceof Player){
			$furnace = $this->position->getWorld()->getTile($this->position);
			if($furnace instanceof TileFurnace && $furnace->canOpenWith($item->getCustomName())){
				$player->setCurrentWindow($furnace->getInventory());
			}
		}

		return true;
	}

	public function onScheduledUpdate() : void{
		$world = $this->position->getWorld();
		$furnace = $world->getTile($this->position);
		if($furnace instanceof TileFurnace && $furnace->onUpdate()){
			if(mt_rand(1, 60) === 1){ //in vanilla this is between 1 and 5 seconds; try to average about 3
				$world->addSound($this->position, $furnace->getFurnaceType()->getCookSound());
			}
			$world->scheduleDelayedBlockUpdate($this->position, 1); //TODO: check this
		}
	}
}