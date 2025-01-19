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

class Sheep extends Living{

  public const NETWORK_ID = EntityIds::PIG;
	private bool $isBaby = false;
	private int $growUpTime = 6000;

	public static function getNetworkTypeId() : string{
		return EntityIds::PIG;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return $this->isBaby
			? new EntitySizeInfo(0.45, 0.45)
			: new EntitySizeInfo(0.9, 0.9);
	}

	public function getName() : string{
		return "Pig";
	}

	public function getDrops() : array{
		if($this->isBaby){
			return [];
		}

		return [
			VanillaItems::RAW_PORKCHOP()->setCount(mt_rand(1, 3))
		];
	}

	public function getXpDropAmount() : int{
		return mt_rand(1, 3);
	}

	public function onInteract(Player $player, Vector3 $clickPos) : bool{
		$item = $player->getInventory()->getItemInHand();

		if($item->equals(VanillaItems::BUCKET(), true)){
			$player->getInventory()->addItem(VanillaItems::MILK_BUCKET());
			return true;
		}

		if($item->equals(VanillaItems::WHEAT(), true) && $this->isBaby){
			$this->growUp();
			return true;
		}

		return parent::onInteract($player, $clickPos);
	}

	public function onUpdate(int $currentTick) : bool{
		$nearestPlayer = $this->getNearestPlayer();

		if($nearestPlayer !== null){
			$distance = $this->getPosition()->distance($nearestPlayer->getPosition());

			if($distance <= 6 && $nearestPlayer->getInventory()->contains(VanillaItems::WHEAT())){
				$this->moveTowards($nearestPlayer->getPosition());
			}
		}

		if($this->isBaby && $currentTick % 20 === 0){
			$this->growUpTime--;
			if($this->growUpTime <= 0){
				$this->growUp();
			}
		}

		return parent::onUpdate($currentTick);
	}

	private function getNearestPlayer() : ?Player{
		$nearestPlayer = null;
		$nearestDistance = PHP_FLOAT_MAX;

		foreach($this->getWorld()->getPlayers() as $player){
			$distance = $this->getPosition()->distance($player->getPosition());
			if($distance < $nearestDistance){
				$nearestPlayer = $player;
				$nearestDistance = $distance;
			}
		}

		return $nearestPlayer;
	}

	private function moveTowards(Vector3 $target) : void{
		$direction = new Vector3(
			$target->x - $this->getPosition()->x,
			$target->y - $this->getPosition()->y,
			$target->z - $this->getPosition()->z
		);
		$direction = $direction->normalize();
		$this->motion->x = $direction->x * 0.2;
		$this->motion->z = $direction->z * 0.2;
	}

	private function growUp() : void{
		$this->isBaby = false;
		$this->setSize(new EntitySizeInfo(0.9, 0.9));
	}
}
