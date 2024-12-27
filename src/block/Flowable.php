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
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;

/**
 * "Flowable" blocks are destroyed if water flows into the same space as the block. These blocks usually don't have any
 * collision boxes, and can't provide support for other blocks.
 */
abstract class Flowable extends Transparent{

	public function canBeFlowedInto() : bool{
		return true;
	}

	public function isSolid() : bool{
		return false;
	}

	public function canBePlacedAt(Block $blockReplace, Vector3 $clickVector, int $face, bool $isClickedBlock) : bool{
		return (!$this->canBeFlowedInto() || !$blockReplace instanceof Liquid) &&
			parent::canBePlacedAt($blockReplace, $clickVector, $face, $isClickedBlock);
	}

	/**
	 * @return AxisAlignedBB[]
	 */
	protected function recalculateCollisionBoxes() : array{
		return [];
	}

	public function getSupportType(int $facing) : SupportType{
		return SupportType::NONE;
	}
}
