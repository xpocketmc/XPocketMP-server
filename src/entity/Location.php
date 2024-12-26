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

namespace pocketmine\entity;

use pocketmine\math\Vector3;
use pocketmine\world\Position;
use pocketmine\world\World;

class Location extends Position{

	public float $yaw;
	public float $pitch;

	public function __construct(float $x, float $y, float $z, ?World $world, float $yaw, float $pitch){
		$this->yaw = $yaw;
		$this->pitch = $pitch;
		parent::__construct($x, $y, $z, $world);
	}

	/**
	 * @return Location
	 */
	public static function fromObject(Vector3 $pos, ?World $world, float $yaw = 0.0, float $pitch = 0.0){
		return new Location($pos->x, $pos->y, $pos->z, $world ?? (($pos instanceof Position) ? $pos->world : null), $yaw, $pitch);
	}

	/**
	 * Return a Location instance
	 */
	public function asLocation() : Location{
		return new Location($this->x, $this->y, $this->z, $this->world, $this->yaw, $this->pitch);
	}

	public function getYaw() : float{
		return $this->yaw;
	}

	public function getPitch() : float{
		return $this->pitch;
	}

	public function __toString(){
		return "Location (world=" . ($this->isValid() ? $this->getWorld()->getDisplayName() : "null") . ", x=$this->x, y=$this->y, z=$this->z, yaw=$this->yaw, pitch=$this->pitch)";
	}

	public function equals(Vector3 $v) : bool{
		if($v instanceof Location){
			return parent::equals($v) && $v->yaw == $this->yaw && $v->pitch == $this->pitch;
		}
		return parent::equals($v);
	}
}
