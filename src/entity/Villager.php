<?php

/*
 *
 *  __   _______           _        _   __  __  _____      __  __ _____
 *  \ \ / /  __ \         | |      | | |  \/  |/ ____|    |  \/  |  __ \
 *   \ V /| |__) |__   ___| | _____| |_| \  / | |   ______| \  / | |__) |
 *    > < |  ___/ _ \ / __| |/ / _ \ __| |\/| | |  |______| |\/| |  ___/
 *   / . \| |  | (_) | (__|   <  __/ |_| |  | | |____     | |  | | |
 *  /_/ \_\_|   \___/ \___|_|\_\___|\__|_|  |_|\_____|    |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @aut>...
 * @link http://www.pocketmine.net/
 *
 */

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataCollection;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;

class Villager extends Living implements Ageable{
	public const PROFESSION_FARMER = 0;
	public const PROFESSION_LIBRARIAN = 1;
	public const PROFESSION_PRIEST = 2;
	public const PROFESSION_BLACKSMITH = 3;
	public const PROFESSION_BUTCHER = 4;

	private const TAG_PROFESSION = "Profession"; //TAG_Int

	public static function getNetworkTypeId() : string{ return EntityIds::VILLAGER; }

	private bool $baby = false;
	private int $profession = self::PROFESSION_FARMER;

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.8, 0.6); //TODO: eye height??
	}

	public function getName() : string{
		return "Villager";
	}

	protected function initEntity(CompoundTag $nbt) : void{
		parent::initEntity($nbt);

		/** @var int $profession */
		$profession = $nbt->getInt(self::TAG_PROFESSION, self::PROFESSION_FARMER);

		if($profession > 4 || $profession < 0){
			$profession = self::PROFESSION_FARMER;
		}

		$this->setProfession($profession);
	}

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();
		$nbt->setInt(self::TAG_PROFESSION, $this->getProfession());

		return $nbt;
	}

	/**
	 * Sets the villager profession
	 */
	public function setProfession(int $profession) : void{
		$this->profession = $profession; //TODO: validation
		$this->networkPropertiesDirty = true;
	}

	public function getProfession() : int{
		return $this->profession;
	}

	public function isBaby() : bool{
		return $this->baby;
	}

	protected function syncNetworkData(EntityMetadataCollection $properties) : void{
		parent::syncNetworkData($properties);
		$properties->setGenericFlag(EntityMetadataFlags::BABY, $this->baby);

		$properties->setInt(EntityMetadataProperties::VARIANT, $this->profession);
	}
}
