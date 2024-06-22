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
 * @author PocketMine Team
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
use pocketmine\world\World;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\Crops;
use pocketmine\block\Farmland;
use pocketmine\math\Vector3;

class Villager extends Living implements Ageable {
    public const PROFESSION_FARMER = 0;
    public const PROFESSION_LIBRARIAN = 1;
    public const PROFESSION_PRIEST = 2;
    public const PROFESSION_BLACKSMITH = 3;
    public const PROFESSION_BUTCHER = 4;

    private const TAG_PROFESSION = "Profession"; // TAG_Int

    private bool $baby = false;
    private int $profession = self::PROFESSION_FARMER;

    /** @var array<string, int> */
    private array $villageBoundaries = [ // Defining the village boundaries
        'x_min' => 0,
        'x_max' => 100,
        'z_min' => 0,
        'z_max' => 100,
    ];

    /** @var array<array<string, int>> */
    private array $farmPlots = [];

    public static function getNetworkTypeId() : string {
        return EntityIds::VILLAGER;
    }

    protected function getInitialSizeInfo() : EntitySizeInfo {
        return new EntitySizeInfo(1.8, 0.6); // TODO: eye height??
    }

    public function getName() : string {
        return "Villager";
    }

    protected function initEntity(CompoundTag $nbt) : void {
        parent::initEntity($nbt);

        /** @var int $profession */
        $profession = $nbt->getInt(self::TAG_PROFESSION, self::PROFESSION_FARMER);

        if ($profession > 4 || $profession < 0) {
            $profession = self::PROFESSION_FARMER;
        }

        $this->setProfession($profession);

        // Generate random farm plots
        $this->generateRandomFarmPlots();
    }

    public function saveNBT() : CompoundTag {
        $nbt = parent::saveNBT();
        $nbt->setInt(self::TAG_PROFESSION, $this->getProfession());

        return $nbt;
    }

    /**
     * Sets the villager profession
     */
    public function setProfession(int $profession) : void {
        $this->profession = $profession; // TODO: validation
        $this->networkPropertiesDirty = true;
    }

    public function getProfession() : int {
        return $this->profession;
    }

    public function isBaby() : bool {
        return $this->baby;
    }

    protected function syncNetworkData(EntityMetadataCollection $properties) : void {
        parent::syncNetworkData($properties);
        $properties->setGenericFlag(EntityMetadataFlags::BABY, $this->baby);
        $properties->setInt(EntityMetadataProperties::VARIANT, $this->profession);
    }

    public function onUpdate(int $currentTick) : bool {
        parent::onUpdate($currentTick);

        // Add walking logic
        $this->wanderAround();

        // Add farming logic
        if ($this->profession === self::PROFESSION_FARMER) {
            $this->farmCrops();
        }

        return true;
    }

    private function wanderAround() : void {
        // Simple wandering logic within the village boundaries
        if (mt_rand(0, 100) < 5) { // 5% chance to move every tick
            $newX = mt_rand($this->villageBoundaries['x_min'], $this->villageBoundaries['x_max']);
            $newZ = mt_rand($this->villageBoundaries['z_min'], $this->villageBoundaries['z_max']);
            $this->moveTo($newX, (int) $this->getPosition()->getY(), $newZ);
        }
    }

    private function farmCrops() : void {
        // Logic to find a farm plot and perform farming actions
        foreach ($this->farmPlots as $plot) {
            if ($this->distanceSquared($plot['x'], $plot['y'], $plot['z']) < 2) {
                // Simulate farming by planting and harvesting crops
                $this->harvestAndReplant($plot['x'], $plot['y'], $plot['z']);
            } else {
                // Move towards the farm plot
                $this->moveTo($plot['x'], $plot['y'], $plot['z']);
            }
        }
    }

    private function harvestAndReplant(int $x, int $y, int $z) : void {
        $world = $this->getWorld();
        $block = $world->getBlockAt(new Vector3($x, $y, $z));

        // Check if the block is a crop and fully grown
        if ($block instanceof Crops && $block->getAge() === Crops::MAX_AGE) {
            // Harvest the crop
            $world->useBreakOn(new Vector3($x, $y, $z));

            // Replant the crop
            $world->setBlockAt(new Vector3($x, $y, $z), BlockLegacyIds::WHEAT_BLOCK);
        }
    }

    private function distanceSquared(int $x, int $y, int $z) : float {
        $pos = $this->getPosition();
        return ($pos->getX() - $x) ** 2 + ($pos->getY() - $y) ** 2 + ($pos->getZ() - $z) ** 2;
    }

    private function moveTo(int $x, int $y, int $z) : void {
        // Logic to move villager to the specified coordinates
        $this->setPosition(new Vector3($x, $y, $z));
    }

    private function generateRandomFarmPlots() : void {
        // Generate a set of random farm plots within the village boundaries
        $numPlots = mt_rand(1, 5); // Number of farm plots to generate
        for ($i = 0; $i < $numPlots; $i++) {
            $x = mt_rand($this->villageBoundaries['x_min'], $this->villageBoundaries['x_max']);
            $z = mt_rand($this->villageBoundaries['z_min'], $this->villageBoundaries['z_max']);
            $y = $this->getWorld()->getHighestBlockAt($x, $z)->getPosition()->getY(); // Get the highest Y coordinate at (x, z)
            $this->farmPlots[] = ['x' => $x, 'y' => $y, 'z' => $z];
        }
    }
}
