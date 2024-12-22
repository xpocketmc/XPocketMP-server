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
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\RailConnectionInfo;
use pocketmine\block\utils\RailPoweredByRedstone;
use pocketmine\data\bedrock\block\BlockLegacyMetadata;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\math\Facing;
use pocketmine\player\Player;
use pocketmine\world\World;

class RedstoneRail extends BaseRail{
	use RailPoweredByRedstone;

	private int $railShape = BlockLegacyMetadata::RAIL_STRAIGHT_NORTH_SOUTH;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->railShape($this->railShape);
		$w->bool($this->isPowered());
	}

	protected function setShapeFromConnections(array $connections) : void{
		$railShape = self::searchState($connections, RailConnectionInfo::CONNECTIONS) ?? self::searchState($connections, RailConnectionInfo::CURVE_CONNECTIONS);
		if($railShape === null){
			throw new \InvalidArgumentException("No rail shape matches these connections");
		}
		$this->railShape = $railShape;
	}

	protected function getCurrentShapeConnections() : array{
		return RailConnectionInfo::CURVE_CONNECTIONS[$this->railShape] ?? RailConnectionInfo::CONNECTIONS[$this->railShape];
	}

	public function getShape() : int{
		return $this->railShape;
	}

	public function setShape(int $shape) : self{
		if(!isset(RailConnectionInfo::CONNECTIONS[$shape]) && !isset(RailConnectionInfo::CURVE_CONNECTIONS[$shape])){
			throw new \InvalidArgumentException("Invalid shape, must be one of " . implode(", ", [...array_keys(RailConnectionInfo::CONNECTIONS), ...array_keys(RailConnectionInfo::CURVE_CONNECTIONS)]));
		}
		$this->railShape = $shape;
		return $this;
	}

	public function onRedstoneUpdate(World $world, int $powerLevel) : void{
		$powered = $powerLevel > 0;
		if($this->isPowered() !== $powered){
			$this->setPowered($powered);
			$world->setBlock($this->position, $this);
		}
	}

	public function onNeighborChange(World $world) : void{
		$powerLevel = $world->getRedstonePowerAt($this->position);
		$this->onRedstoneUpdate($world, $powerLevel);
	}

	public function isPowered() : bool{
		return $this->getPersistentData()->getBool("powered", false);
	}

	public function setPowered(bool $powered) : void{
		$this->getPersistentData()->setBool("powered", $powered);
	}

	public function onInteract(Player $player) : bool{
		$this->setPowered(!$this->isPowered());
		$player->getWorld()->setBlock($this->position, $this);
		return true;
	}
}
