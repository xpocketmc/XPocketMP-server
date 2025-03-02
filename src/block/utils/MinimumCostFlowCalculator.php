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

namespace pocketmine\block\utils;

use pocketmine\block\Block;
use pocketmine\math\Facing;
use pocketmine\world\World;
use function array_fill_keys;
use function intdiv;
use function min;

/**
 * Calculates the path(s) of least resistance for liquid flow.
 */
final class MinimumCostFlowCalculator{

	private const CAN_FLOW_DOWN = 1;
	private const CAN_FLOW = 0;
	private const BLOCKED = -1;

	/** @var int[] */
	private array $flowCostVisited = [];

	/**
	 * @phpstan-param \Closure(Block) : bool $canFlowInto
	 */
	public function __construct(
		private World $world,
		private int $flowDecayPerBlock,
		private \Closure $canFlowInto
	){}

	private function calculateFlowCost(int $blockX, int $blockY, int $blockZ, int $accumulatedCost, int $maxCost, int $originOpposite, int $lastOpposite) : int{
		$cost = 1000;

		foreach(Facing::HORIZONTAL as $j){
			if($j === $originOpposite || $j === $lastOpposite){
				continue;
			}
			[$dx, $dy, $dz] = Facing::OFFSET[$j];
			$x = $blockX + $dx;
			$y = $blockY + $dy;
			$z = $blockZ + $dz;

			if(!isset($this->flowCostVisited[$hash = World::blockHash($x, $y, $z)])){
				if(!$this->world->isInWorld($x, $y, $z) || !$this->canFlowInto($this->world->getBlockAt($x, $y, $z))){
					$this->flowCostVisited[$hash] = self::BLOCKED;
				}elseif($this->world->getBlockAt($x, $y - 1, $z)->canBeFlowedInto()){
					$this->flowCostVisited[$hash] = self::CAN_FLOW_DOWN;
				}else{
					$this->flowCostVisited[$hash] = self::CAN_FLOW;
				}
			}

			$status = $this->flowCostVisited[$hash];

			if($status === self::BLOCKED){
				continue;
			}elseif($status === self::CAN_FLOW_DOWN){
				return $accumulatedCost;
			}

			if($accumulatedCost >= $maxCost){
				continue;
			}

			$realCost = $this->calculateFlowCost($x, $y, $z, $accumulatedCost + 1, $maxCost, $originOpposite, Facing::opposite($j));

			if($realCost < $cost){
				$cost = $realCost;
			}
		}

		return $cost;
	}

	/**
	 * @return int[]
	 */
	public function getOptimalFlowDirections(int $originX, int $originY, int $originZ) : array{
		$flowCost = array_fill_keys(Facing::HORIZONTAL, 1000);
		$maxCost = intdiv(4, $this->flowDecayPerBlock);
		foreach(Facing::HORIZONTAL as $j){
			[$dx, $dy, $dz] = Facing::OFFSET[$j];
			$x = $originX + $dx;
			$y = $originY + $dy;
			$z = $originZ + $dz;

			if(!$this->world->isInWorld($x, $y, $z) || !$this->canFlowInto($this->world->getBlockAt($x, $y, $z))){
				$this->flowCostVisited[World::blockHash($x, $y, $z)] = self::BLOCKED;
			}elseif($this->world->getBlockAt($x, $y - 1, $z)->canBeFlowedInto()){
				$this->flowCostVisited[World::blockHash($x, $y, $z)] = self::CAN_FLOW_DOWN;
				$flowCost[$j] = $maxCost = 0;
			}elseif($maxCost > 0){
				$this->flowCostVisited[World::blockHash($x, $y, $z)] = self::CAN_FLOW;
				$opposite = Facing::opposite($j);
				$flowCost[$j] = $this->calculateFlowCost($x, $y, $z, 1, $maxCost, $opposite, $opposite);
				$maxCost = min($maxCost, $flowCost[$j]);
			}
		}

		$this->flowCostVisited = [];

		$minCost = min($flowCost);

		$isOptimalFlowDirection = [];

		foreach($flowCost as $facing => $cost){
			if($cost === $minCost){
				$isOptimalFlowDirection[] = $facing;
			}
		}

		return $isOptimalFlowDirection;
	}

	private function canFlowInto(Block $block) : bool{
		return ($this->canFlowInto)($block);
	}
}
