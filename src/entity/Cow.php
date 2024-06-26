<?php

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\entity\EntitySizeInfo;
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

    public function getDrops() : array{
		return [
			VanillaItems::LEATHER()->setCount(mt_rand(1, 3)),
			VanillaItems::RAW_BEEF()->setCount(mt_rand(1, 4))
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

        // Kirim paket ke setiap pemain yang melihat entitas ini
        foreach($this->getViewers() as $viewer) {
            $viewer->getNetworkSession()->sendDataPacket($pk);
        }
    }

    public function getInitialSizeInfo() : EntitySizeInfo
    {
        return new EntitySizeInfo(1.4, 0.9); // tinggi 1.4 unit, lebar 0.9 unit
    }

    public static function getNetworkTypeId() : string
    {
        return EntityIds::COW;
	}
}
