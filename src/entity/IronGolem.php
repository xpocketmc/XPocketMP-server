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

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemTypeIds;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use function max;
use function min;
use function mt_rand;

class IronGolem extends Living {
	public const NETWORK_ID = EntityIds::IRON_GOLEM;

	protected $width = 1.4;
	protected $height = 2.7;
	protected $gravity = 0.08;
	protected $drag = 0.02;

	private int $crackLevel = 0;
	private ?Living $target = null;

	public function getName() : string {
		return "Iron Golem";
	}

	public function getMaxHealth() : int {
		return 100;
	}

	public function initEntity(CompoundTag $nbt) : void {
		parent::initEntity($nbt);
		$this->setHealth($this->getMaxHealth());
		$this->crackLevel = $nbt->getInt("CrackLevel", 0);
	}

	public function saveNBT() : CompoundTag {
		$nbt = parent::saveNBT();
		$nbt->setInt("CrackLevel", $this->crackLevel);
		return $nbt;
	}

	public function onUpdate(int $currentTick) : bool {
		if ($this->isClosed() || !$this->isAlive()) {
			return false;
		}

		if ($this->target === null) {
			$this->findTarget();
		} else {
			$this->moveToward($this->target->getPosition());
			if ($this->distance($this->target) <= 2) {
				$this->attackEntity($this->target);
			}
		}

		return parent::onUpdate($currentTick);
	}

	private function findTarget() : void {
		foreach ($this->getWorld()->getEntities() as $entity) {
			if ($entity instanceof Living && !$entity instanceof self && $this->distance($entity) <= 16) {
				$this->target = $entity;
				break;
			}
		}
	}

	private function moveToward(Vector3 $position) : void {
		$direction = $position->subtract($this->getPosition())->normalize();
		$this->motion->x = $direction->x * $this->getSpeed();
		$this->motion->z = $direction->z * $this->getSpeed();
		$this->move($this->motion->x, $this->motion->y, $this->motion->z);
	}

	private function getSpeed() : float {
		return 0.25;
	}

	public function attackEntity(Living $target) : void {
		$damage = mt_rand(15, 43);
		$target->attack(new EntityDamageByEntityEvent($this, $target, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $damage));

		$knockback = 0.5;
		$target->setMotion($target->getMotion()->add($this->motion->multiply($knockback))->add(0, 0.4, 0));
	}

	public function onInteract(Player $player, Item $item, Vector3 $clickPos) : bool {
		if ($item->getTypeId() === ItemTypeIds::IRON_INGOT && $this->crackLevel > 0) {
			$this->crackLevel--;
			$this->heal(25);
			$item->pop();
			return true;
		}
		return false;
	}

	public function getDrops() : array {
		return [
			Item::get(ItemTypeIds::IRON_INGOT, 0, mt_rand(3, 5)),
			Item::get(ItemTypeIds::POPPY, 0, mt_rand(0, 2))
		];
	}

	public function setCrackLevel(int $level) : void {
		$this->crackLevel = max(0, min(3, $level));
	}

	public function getCrackLevel() : int {
		return $this->crackLevel;
	}
}
