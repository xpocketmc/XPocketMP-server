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

namespace pocketmine\entity;

use pocketmine\entity\utils\RandomSwimDirection;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\Location;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\world\particle\BubbleParticle;

class Salmon extends WaterAnimal
{
    public const NETWORK_ID = EntityIds::SALMON;
    public const DATA_FLAG_IMMOBILE = 'DATA_FLAG_IMMOBILE';

    private Vector3 $swimDirection;
    private int $changeDirectionTicks = 0;

    public function __construct(Location $location, ?CompoundTag $nbt = null)
    {
        parent::__construct($location, $nbt);
        $this->setMaxHealth(3);
        $this->setHealth($this->getMaxHealth());
        $this->swimDirection = new Vector3(0, 0, 0);
    }

    public function getName(): string
    {
        return "Salmon";
    }

    public function getInitialSizeInfo(): EntitySizeInfo
    {
        return new EntitySizeInfo(0.4, 0.7);
    }

    public static function getNetworkTypeId(): string
    {
        return "minecraft:salmon";
    }

    public function initEntity(CompoundTag $nbt): void
    {
        parent::initEntity($nbt);
        $this->setGenericFlag(self::DATA_FLAG_IMMOBILE, false);
    }

    public function getDrops(): array
    {
        return [
            VanillaItems::RAW_SALMON()
        ];
    }

    public function getSpeed(): float
    {
        return 1.2;
    }

    protected function entityBaseTick(int $tickDiff = 1): bool
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

    private function changeSwimDirection(): void
    {
        $this->swimDirection = RandomSwimDirection::generate();
    }

    public function updateMovement(bool $teleport = false): void
    {
        parent::updateMovement($teleport);
    }

    private function setGenericFlag(string $flag, bool $value): void
    {
        $this->propertyManager->setGenericFlag($flag, $value);
    }
}
