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
use pocketmine\level\sound\DoorSound;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Bearing;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Trapdoor extends Transparent{
	public const MASK_UPPER = 0x04;
	public const MASK_OPENED = 0x08;
	public const MASK_SIDE = 0x03;
	public const MASK_SIDE_SOUTH = 2;
	public const MASK_SIDE_NORTH = 3;
	public const MASK_SIDE_EAST = 0;
	public const MASK_SIDE_WEST = 1;

	protected $id = self::TRAPDOOR;

	/** @var int */
	protected $facing = Facing::NORTH;
	/** @var bool */
	protected $open = false;
	/** @var bool */
	protected $top = false;

	public function __construct(){

	}

	protected function writeStateToMeta() : int{
		return (5 - $this->facing) | ($this->top ? self::MASK_UPPER : 0) | ($this->open ? self::MASK_OPENED : 0);
	}

	public function readStateFromMeta(int $meta) : void{
		//TODO: in PC the values are reversed (facing - 2)

		$this->facing = 5 - ($meta & 0x03);
		$this->top = ($meta & self::MASK_UPPER) !== 0;
		$this->open = ($meta & self::MASK_OPENED) !== 0;
	}

	public function getStateBitmask() : int{
		return 0b1111;
	}

	public function getName() : string{
		return "Wooden Trapdoor";
	}

	public function getHardness() : float{
		return 3;
	}

	protected function recalculateBoundingBox() : ?AxisAlignedBB{
		$f = 0.1875;

		if($this->top){
			$bb = new AxisAlignedBB(0, 1 - $f, 0, 1, 1, 1);
		}else{
			$bb = new AxisAlignedBB(0, 0, 0, 1, $f, 1);
		}

		if($this->open){
			if($this->facing === Facing::NORTH){
				$bb->setBounds(0, 0, 1 - $f, 1, 1, 1);
			}elseif($this->facing === Facing::SOUTH){
				$bb->setBounds(0, 0, 0, 1, 1, $f);
			}elseif($this->facing === Facing::WEST){
				$bb->setBounds(1 - $f, 0, 0, 1, 1, 1);
			}elseif($this->facing === Facing::EAST){
				$bb->setBounds(0, 0, 0, $f, 1, 1);
			}
		}

		return $bb;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool{
		if($player !== null){
			$this->facing = Bearing::toFacing(Bearing::opposite($player->getDirection()));
		}
		if(($clickVector->y > 0.5 and $face !== Facing::UP) or $face === Facing::DOWN){
			$this->top = true;
		}

		return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function onActivate(Item $item, Player $player = null) : bool{
		$this->open = !$this->open;
		$this->level->setBlock($this, $this);
		$this->level->addSound(new DoorSound($this));
		return true;
	}

	public function getToolType() : int{
		return BlockToolType::TYPE_AXE;
	}

	public function getFuelTime() : int{
		return 300;
	}
}
