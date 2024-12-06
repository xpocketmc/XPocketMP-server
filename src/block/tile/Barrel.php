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

namespace XPocketMPlock\tile;

use XPocketMPlock\inventory\BarrelInventory;
use XPocketMP\math\Vector3;
use XPocketMP\nbt\tag\CompoundTag;
use XPocketMP\world\World;

class Barrel extends Spawnable implements Container, Nameable{
	use NameableTrait;
	use ContainerTrait;

	protected BarrelInventory $inventory;

	public function __construct(World $world, Vector3 $pos){
		parent::__construct($world, $pos);
		$this->inventory = new BarrelInventory($this->position);
	}

	public function readSaveData(CompoundTag $nbt) : void{
		$this->loadName($nbt);
		$this->loadItems($nbt);
	}

	protected function writeSaveData(CompoundTag $nbt) : void{
		$this->saveName($nbt);
		$this->saveItems($nbt);
	}

	public function close() : void{
		if(!$this->closed){
			$this->inventory->removeAllViewers();
			parent::close();
		}
	}

	public function getInventory() : BarrelInventory{
		return $this->inventory;
	}

	public function getRealInventory() : BarrelInventory{
		return $this->inventory;
	}

	public function getDefaultName() : string{
		return "Barrel";
	}
}