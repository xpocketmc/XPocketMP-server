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

use pocketmine\block\Block;
use pocketmine\utils\Random;
use pocketmine\world\chunk\ChunkManager;
use pocketmine\world\generator\populator\Populator;

abstract class Biome{

	public const MAX_BIOMES = 256;

	private int $id;
	private bool $registered = false;

	/** @var Populator[] */
	private array $populators = [];

	private int $minElevation;
	private int $maxElevation;

	/** @var Block[] */
	private array $groundCover = [];

	protected float $rainfall = 0.5;
	protected float $temperature = 0.5;

	public function clearPopulators() : void{
		$this->populators = [];
	}

	public function addPopulator(Populator $populator) : void{
		$this->populators[] = $populator;
	}

	public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ, Random $random) : void{
		foreach($this->populators as $populator){
			$populator->populate($world, $chunkX, $chunkZ, $random);
		}
	}

	/**
	 * @return Populator[]
	 */
	public function getPopulators() : array{
		return $this->populators;
	}

	public function setId(int $id) : void{
		if(!$this->registered){
			$this->registered = true;
			$this->id = $id;
		}
	}

	public function getId() : int{
		return $this->id;
	}

	abstract public function getName() : string;

	public function getMinElevation() : int{
		return $this->minElevation;
	}

	public function getMaxElevation() : int{
		return $this->maxElevation;
	}

	public function setElevation(int $min, int $max) : void{
		$this->minElevation = $min;
		$this->maxElevation = $max;
	}

	/**
	 * @return Block[]
	 */
	public function getGroundCover() : array{
		return $this->groundCover;
	}

	/**
	 * @param Block[] $covers
	 */
	public function setGroundCover(array $covers) : void{
		$this->groundCover = $covers;
	}

	public function getTemperature() : float{
		return $this->temperature;
	}

	public function getRainfall() : float{
		return $this->rainfall;
	}
}
