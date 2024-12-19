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
 * Developed: ClousClouds, PMMP Team
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\utils\RandomSwimDirection;
use pocketmine\world\particle\BubbleParticle;
use function error_log;

class Salmon extends WaterAnimal
{
	public const NETWORK_ID = EntityIds::SALMON;
	public const DATA_FLAG_IMMOBILE = 'DATA_FLAG_IMMOBILE';

	private Vector3 $swimDirection;
	private int $changeDirectionTicks = 0;
	/** @var PropertyManager */
	private $propertyManager; // Define the propertyManager

	public function __construct(Location $location, ?CompoundTag $nbt = null)
	{
		parent::__construct($location, $nbt);
		$this->setMaxHealth(3);
		$this->setHealth($this->getMaxHealth());
		$this->swimDirection = new Vector3(0, 0, 0);
		$this->propertyManager = new PropertyManager(); // Initialize the propertyManager
	}

	public function getName() : string
	{
		return "Salmon";
	}

	public function getInitialSizeInfo() : EntitySizeInfo
	{
		return new EntitySizeInfo(0.4, 0.7);
	}

	public static function getNetworkTypeId() : string
	{
		return "minecraft:salmon";
	}

	public function initEntity(CompoundTag $nbt) : void
	{
		parent::initEntity($nbt);
		$this->setGenericFlag(self::DATA_FLAG_IMMOBILE, false);
	}

	public function getDrops() : array
	{
		return [
			VanillaItems::RAW_SALMON()
		];
	}

	public function getSpeed() : float
	{
		return 1.2;
	}

	protected function entityBaseTick(int $tickDiff = 1) : bool
	{
		$hasUpdate = parent::entityBaseTick($tickDiff);

		if ($this->changeDirectionTicks-- <= 0) {
			$this->changeSwimDirection();
			$this->changeDirectionTicks = 100;
		}

		$this->move($this->swimDirection->x, $this->swimDirection->y, $this->swimDirection->z);
		$this->getWorld()->addParticle($this->getLocation()->add(0, 0.5, 0), new BubbleParticle());

		return $hasUpdate;
	}

	private function changeSwimDirection() : void
	{
		$this->swimDirection = RandomSwimDirection::generate();
	}

	public function updateMovement(bool $teleport = false) : void
	{
		parent::updateMovement($teleport);
	}

	private function setGenericFlag(string $flag, bool $value) : void
	{
		if ($this->propertyManager !== null) {
			$this->propertyManager->setGenericFlag($flag, $value);
		} else {
			error_log("propertyManager is null in Salmon entity");
		}
	}
}
