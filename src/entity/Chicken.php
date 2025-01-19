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

use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use function mt_rand;
use const PHP_FLOAT_MAX;

class Chicken extends Living{

  public const NETWORK_ID = EntityIds::CHICKEN;

	public static function getNetworkTypeId() : string{
		return EntityIds::CHICKEN;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.8, 0.6);
	}

	public function getName() : string{
		return "Chicken";
	}

	public function getDrops() : array{
		return [
			VanillaItems::RAW_CHICKEN()->setCount(mt_rand(1)),
			VanillaItems::FEATHER()->setCount(mt_rand(0, 2))
		];
	}

	public function getXpDropAmount() : int{
		return mt_rand(1, 3);
	}
}