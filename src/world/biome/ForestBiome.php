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

namespace pocketmine\world\biome;

use pocketmine\world\generator\object\TreeType;
use pocketmine\world\generator\populator\TallGrass;
use pocketmine\world\generator\populator\Tree;

class ForestBiome extends GrassyBiome{
	private TreeType $type;

	public function __construct(?TreeType $type = null){
		parent::__construct();

		$this->type = $type ?? TreeType::OAK;

		$trees = new Tree($type);
		$trees->setBaseAmount(5);
		$this->addPopulator($trees);

		$tallGrass = new TallGrass();
		$tallGrass->setBaseAmount(3);

		$this->addPopulator($tallGrass);

		$this->setElevation(63, 81);

		if($this->type === TreeType::BIRCH){
			$this->temperature = 0.6;
			$this->rainfall = 0.5;
		}else{
			$this->temperature = 0.7;
			$this->rainfall = 0.8;
		}
	}

	public function getName() : string{
		return $this->type->getDisplayName() . " Forest";
	}
}
