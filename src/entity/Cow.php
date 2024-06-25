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

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use function mt_rand;

class Cow extends Living
{
          public const NETWORK_ID = EntityIds::COW;

          public function __construct(Level $level, CompoundTag $nbt)
          {
                          parent::__construct($level, $nbt);
          }

          public function getName() : string
          {
                          return "Cow";
          }

          public function getDrops() : array
          {
                          return [
                                  Item::get(Item::RAW_BEEF, 0, mt_rand(1, 3)), // Drop 1-3 raw beef
                                  Item::get(Item::LEATHER, 0, mt_rand(0, 2))  // Drop 0-2 leather
                          ];
          }

          protected function addSpawnPacket(Player $player) : void
          {
                          $pk = new AddActorPacket();
                          $pk->type = self::NETWORK_ID;
                          $pk->entityRuntimeId = $this->getId();
                          $pk->position = $this->asVector3();
                          $pk->motion = $this->getMotion();
                          $pk->yaw = $this->yaw;
                          $pk->pitch = $this->pitch;
                          $pk->headYaw = $this->yaw;

                          $this->server->broadcastPacket($this->getViewers(), $pk);
          }
}
