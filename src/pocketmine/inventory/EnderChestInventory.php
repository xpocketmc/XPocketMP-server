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
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\entity\Human;
use pocketmine\level\Level;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;
use pocketmine\tile\EnderChest;

class EnderChestInventory extends ChestInventory{

	/** @var FakeBlockMenu */
	protected $holder;

	public function __construct(Human $owner){
		ContainerInventory::__construct(new FakeBlockMenu($this, $owner->getPosition()));
	}

	public function getNetworkType() : int{
		return WindowTypes::CONTAINER;
	}

	public function getName() : string{
		return "EnderChest";
	}

	public function getDefaultSize() : int{
		return 27;
	}

	/**
	 * Set the holders position to that of a tile
	 *
	 * @param EnderChest $enderChest
	 */
	public function setHolderPosition(EnderChest $enderChest){
		$this->holder->setComponents($enderChest->getX(), $enderChest->getY(), $enderChest->getZ());
		$this->holder->setLevel($enderChest->getLevel());
	}

	/**
	 * This override is here for documentation and code completion purposes only.
	 * @return FakeBlockMenu
	 */
	public function getHolder(){
		return $this->holder;
	}

}