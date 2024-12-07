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

namespace pocketmine\world\generator\populator;

use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeTags;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\world\generator\object\TreeFactory;
use pocketmine\world\generator\object\TreeType;
use function in_array;

class Tree implements Populator{
	private int $randomAmount = 1;
	private int $baseAmount = 0;
	private array $trees = [];
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

	/**
	 * Add tree types to the populator
	 *
	 * @param string $treeType Tree type (examples: "Oak", "Cherry", etc.)
	 * @throws InvalidArgumentException If the tree type is invalid
	 */
	public function addTree(string $treeType) : void {
		$allowedTypes = ["Oak", "Spruce", "Birch", "Jungle", "Cherry"];
		if (!in_array($treeType, $allowedTypes, true)) {
			throw new InvalidArgumentException("Invalid tree type: $treeType");
		}

		if (!in_array($treeType, $this->trees, true)) {
			$this->trees[] = $treeType;
		}
	}

	/**
	 * Get a list of trees that have been added
	 */
	public function getTrees() : array {
		return $this->trees;
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
