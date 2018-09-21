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
use pocketmine\item\ItemFactory;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Bearing;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\Player;

class CocoaBlock extends Transparent{

	protected $id = self::COCOA_BLOCK;

	/** @var int */
	protected $facing = Facing::NORTH;
	/** @var int */
	protected $age = 0;

	public function __construct(){

	}

	protected function writeStateToMeta() : int{
		return Bearing::fromFacing(Facing::opposite($this->facing)) | ($this->age << 2);
	}

	public function readStateFromMeta(int $meta) : void{
		$this->facing = Facing::opposite(Bearing::toFacing($meta & 0x03));
		$this->age = $meta >> 2;
	}

	public function getStateBitmask() : int{
		return 0b1111;
	}

	public function getName() : string{
		return "Cocoa Block";
	}

	public function getHardness() : float{
		return 0.2;
	}

	public function getToolType() : int{
		return BlockToolType::TYPE_AXE;
	}

	//TODO

	public function isAffectedBySilkTouch() : bool{
		return false;
	}
}
