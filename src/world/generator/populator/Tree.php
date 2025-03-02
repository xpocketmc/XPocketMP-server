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

use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeTags;
use pocketmine\utils\Random;
use pocketmine\world\chunk\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\world\generator\object\TreeFactory;
use pocketmine\world\generator\object\TreeType;

class Tree implements Populator{
	private int $randomAmount = 1;
	private int $baseAmount = 0;
	private TreeType $type;

	/**
	 * @param TreeType|null $type default oak
	 */
	public function __construct(?TreeType $type = null){
		$this->type = $type ?? TreeType::OAK;
	}

	public function setRandomAmount(int $amount) : void{
		$this->randomAmount = $amount;
	}

	public function setBaseAmount(int $amount) : void{
		$this->baseAmount = $amount;
	}

	public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random) : void{
		$amount = $random->nextRange(0, $this->randomAmount) + $this->baseAmount;
		for($i = 0; $i < $amount; ++$i){
			$x = $random->nextRange($chunkX << Chunk::COORD_BIT_SIZE, ($chunkX << Chunk::COORD_BIT_SIZE) + Chunk::EDGE_LENGTH);
			$z = $random->nextRange($chunkZ << Chunk::COORD_BIT_SIZE, ($chunkZ << Chunk::COORD_BIT_SIZE) + Chunk::EDGE_LENGTH);
			$y = $this->getHighestWorkableBlock($world, $x, $z);
			if($y === -1){
				continue;
			}
			$tree = TreeFactory::get($random, $this->type);
			$transaction = $tree?->getBlockTransaction($world, $x, $y, $z, $random);
			$transaction?->apply();
		}
	}

	private function getHighestWorkableBlock(ChunkManager $world, int $x, int $z) : int{
		for($y = 127; $y >= 0; --$y){
			$b = $world->getBlockAt($x, $y, $z);
			if($b->hasTypeTag(BlockTypeTags::DIRT) || $b->hasTypeTag(BlockTypeTags::MUD)){
				return $y + 1;
			}elseif($b->getTypeId() !== BlockTypeIds::AIR && $b->getTypeId() !== BlockTypeIds::SNOW_LAYER){
				return -1;
			}
		}

		return -1;
	}
}
