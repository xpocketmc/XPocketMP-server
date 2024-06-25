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
 */

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\item\Item;
use pocketmine\item\ItemTypeIds;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\Server;
use function mt_rand;

class Cow extends Living
{
    public const NETWORK_ID = EntityIds::COW;

    private float $yaw;
    private float $pitch;

    public function __construct(Location $location, ?CompoundTag $nbt = null)
    {
        parent::__construct($location, $nbt);
        $this->yaw = $location->getYaw();
        $this->pitch = $location->getPitch();
    }

    public function getName() : string
    {
        return "Cow";
    }

    public function getDrops() : array
    {
        return [
            Item::get(ItemTypeIds::RAW_BEEF, 0, mt_rand(1, 3)), // Drop 1-3 raw beef
        ];
    }

    protected function addSpawnPacket(Player $player) : void
    {
        $pk = new AddActorPacket();
        $pk->type = self::NETWORK_ID;
        $pk->actorRuntimeId = $this->getId();
        $pk->position = $this->getPosition();
        $pk->motion = $this->getMotion();
        $pk->yaw = $this->yaw;
        $pk->pitch = $this->pitch;
        $pk->headYaw = $this->yaw;

        $this->getServer()->broadcastPackets($this->getViewers(), [$pk]);
    }

    public function getInitialSizeInfo() : EntitySizeInfo
    {
        return new EntitySizeInfo(1.4, 0.9); // tinggi 1.4 unit, lebar 0.9 unit
    }

    public static function getNetworkTypeId() : string
    {
        return EntityIds::COW;
    }

    private function getServer(): Server
    {
        return Server::getInstance(); // Adjust this method to correctly get the server instance
    }
}
