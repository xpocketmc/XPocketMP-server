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

namespace pocketmine\block;

use pocketmine\block\utils\SupportType;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use function array_key_first;
use function count;

class ResinClump extends Transparent{

	/** @var int[] */
	protected array $faces = [];

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->facingFlags($this->faces);
	}

	/** @return int[] */
	public function getFaces() : array{ return $this->faces; }

	public function hasFace(int $face) : bool{
		return isset($this->faces[$face]);
	}

	/**
	 * @param int[] $faces
	 * @return $this
	 */
	public function setFaces(array $faces) : self{
		$uniqueFaces = [];
		foreach($faces as $face){
			Facing::validate($face);
			$uniqueFaces[$face] = $face;
		}
		$this->faces = $uniqueFaces;
		return $this;
	}

	/** @return $this */
	public function setFace(int $face, bool $value) : self{
		Facing::validate($face);
		if($value){
			$this->faces[$face] = $face;
		}else{
			unset($this->faces[$face]);
		}
		return $this;
	}

	public function isSolid() : bool{
		return false;
	}

	/**
	 * @return array<AxisAlignedBB>
	 */
	protected function recalculateCollisionBoxes() : array{
		return [];
	}

	public function getSupportType(int $facing) : SupportType{
		return SupportType::NONE;
	}

	public function canBeReplaced() : bool{
		return true;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		$this->faces = $blockReplace instanceof ResinClump ? $blockReplace->faces : [];
		$availableFaces = $this->getAvailableFaces();

		if(count($availableFaces) === 0){
			return false;
		}

		$opposite = Facing::opposite($face);
		$placedFace = isset($availableFaces[$opposite]) ? $opposite : array_key_first($availableFaces);
		$this->faces[$placedFace] = $placedFace;

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function onNearbyBlockChange() : void{
		$changed = false;

		foreach($this->faces as $face){
			if($this->getAdjacentSupportType($face) !== SupportType::FULL){
				unset($this->faces[$face]);
				$changed = true;
			}
		}

		if($changed){
			$world = $this->position->getWorld();
			if(count($this->faces) === 0){
				$world->useBreakOn($this->position);
			}else{
				$world->setBlock($this->position, $this);
			}
		}
	}

	/**
	 * @return array<int, int> $faces
	 */
	private function getAvailableFaces() : array{
		$faces = [];
		foreach(Facing::ALL as $face){
			if(!$this->hasFace($face) && $this->getAdjacentSupportType($face) === SupportType::FULL){
				$faces[$face] = $face;
			}
		}
		return $faces;
	}
}
