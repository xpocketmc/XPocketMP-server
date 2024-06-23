<?php

/**
 * Cow.php
 *
 * @license MIT
 * @link https://github.com/XPocketMP
 * 
 * Teks XPocketMP
 */

namespace pocketmine\entity;

use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityEventPacket;
use pocketmine\scheduler\ClosureTask;
use pocketmine\plugin\PluginBase;

class Cow extends Animal {

    public const NETWORK_ID = EntityIds::COW; 

    private $target = null;

    public function __construct(Location $location, ?CompoundTag $nbt = null) {
        parent::__construct($location, $nbt);
        $this->setMaxHealth(10);
        $this->scheduleUpdate();
    }

    public static function getNetworkTypeId() : string {
        return self::NETWORK_ID;
    }

    protected function getInitialSizeInfo() : EntitySizeInfo {
        return new EntitySizeInfo(1.4, 0.9); // Height and width of the cow
    }

    public function getName() : string {
        return "Cow";
    }

    public function getDrops() : array {
        return [
            ItemFactory::getInstance()->get(ItemIds::RAW_BEEF, 0, mt_rand(1, 3)),
            ItemFactory::getInstance()->get(ItemIds::LEATHER, 0, mt_rand(0, 2))
        ];
    }

    public function interact(Player $player, Vector3 $clickPos) : bool {
        $item = $player->getInventory()->getItemInHand();
        if ($item->getId() === ItemIds::WHEAT) {
            $this->setHealth($this->getMaxHealth());
            $this->broadcastEntityEvent(EntityEventPacket::EAT_GRASS_ANIMATION);
            $item->pop();
            return true;
        }
        return parent::interact($player, $clickPos);
    }

    public function scheduleUpdate() {
        $plugin = PluginBase::getInstance();
        $plugin->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (): void {
            $this->updateMovement();
        }), 20); // Update every second (20 ticks)
    }

    public function updateMovement() {
        if ($this->isAlive()) {
            $this->randomWalk();
        }
    }

    private function randomWalk() {
        if ($this->target === null || $this->target->distanceSquared($this) < 1) {
            $this->target = $this->getRandomDestination();
        }
        $this->moveTowards($this->target);
    }

    private function getRandomDestination(): Vector3 {
        $x = $this->getPosition()->getX() + mt_rand(-10, 10);
        $z = $this->getPosition()->getZ() + mt_rand(-10, 10);
        $y = $this->getWorld()->getHighestBlockAt($x, $z) + 1;
        return new Vector3($x, $y, $z);
    }

    private function moveTowards(Vector3 $target) {
        $direction = $target->subtract($this->getPosition())->normalize();
        $this->motion->x = $direction->x * 0.1;
        $this->motion->z = $direction->z * 0.1;
        $this->yaw = rad2deg(atan2(-$direction->x, $direction->z));
        $this->pitch = rad2deg(-atan2($direction->y, sqrt($direction->x ** 2 + $direction->z ** 2)));
        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
    }
}
