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

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Wood extends Solid{
	const OAK = 0;
	const SPRUCE = 1;
	const BIRCH = 2;
	const JUNGLE = 3;

	protected $id = self::WOOD;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getHardness() : float{
		return 2;
	}

	public function getName() : string{
		static $names = [
			self::OAK => "Oak Wood",
			self::SPRUCE => "Spruce Wood",
			self::BIRCH => "Birch Wood",
			self::JUNGLE => "Jungle Wood",
		];
		return $names[$this->meta & 0x03];
	}

	public function place(Item $item, Block $block, Block $target, int $face, Vector3 $facePos, Player $player = null) : bool{
		$faces = [
			Vector3::SIDE_DOWN => 0,
			Vector3::SIDE_UP => 0,
			Vector3::SIDE_NORTH => 0b1000,
			Vector3::SIDE_SOUTH => 0b1000,
			Vector3::SIDE_WEST => 0b0100,
			Vector3::SIDE_EAST => 0b0100,
		];

		$this->meta = ($this->meta & 0x03) | $faces[$face];
		$this->getLevel()->setBlock($block, $this, true, true);

		return true;
	}

	public function getVariantBitmask() : int{
		return 0x03;
	}

	public function getToolType() : int{
		return Tool::TYPE_AXE;
	}

	public function getFuelTime() : int{
		return 300;
	}
}