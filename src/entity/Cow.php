<?php

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemTypeIds;
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

    public function getDrops() : array
    {
        $drops = [];
        $lootingLevel = $this->getLootingLevel(); // Jika ada mekanisme looting

        // Tambahkan drop item kulit (Leather)
        $leatherDropCount = mt_rand(0, 2 + $lootingLevel); // Drop 0-2 + looting level
        if($leatherDropCount > 0){
            $drops[] = ItemFactory::getInstance()->get(ItemTypeIds::LEATHER, 0, $leatherDropCount);
        }

        // Tambahkan drop item daging mentah (RawBeef)
        $beefDropCount = mt_rand(1, 3 + $lootingLevel); // Drop 1-3 + looting level
        if($beefDropCount > 0){
            $drops[] = ItemFactory::getInstance()->get(ItemTypeIds::RAW_BEEF, 0, $beefDropCount);
        }

        return $drops;
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
        foreach($this->getViewers() as $viewer){
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

    private function getServer(): Server
    {
        return Server::getInstance();
    }
}
