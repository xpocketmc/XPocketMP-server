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

namespace pocketmine\world\generator\populator;

use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\world\generator\object\Ore as ObjectOre;
use pocketmine\world\generator\object\OreType;

class Ore implements Populator{
	/** @var OreType[] */
	private array $oreTypes = [];

	public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random) : void{
		foreach($this->oreTypes as $type){
			$ore = new ObjectOre($random, $type);
			for($i = 0; $i < $ore->type->clusterCount; ++$i){
				$x = $random->nextRange($chunkX << Chunk::COORD_BIT_SIZE, ($chunkX << Chunk::COORD_BIT_SIZE) + Chunk::EDGE_LENGTH - 1);
				$y = $random->nextRange($ore->type->minHeight, $ore->type->maxHeight);
				$z = $random->nextRange($chunkZ << Chunk::COORD_BIT_SIZE, ($chunkZ << Chunk::COORD_BIT_SIZE) + Chunk::EDGE_LENGTH - 1);
				if($ore->canPlaceObject($world, $x, $y, $z)){
					$ore->placeObject($world, $x, $y, $z);
				}
			}
		}
	}

	/**
	 * @param OreType[] $types
	 */
	public function setOreTypes(array $types) : void{
		$this->oreTypes = $types;
	}
}
