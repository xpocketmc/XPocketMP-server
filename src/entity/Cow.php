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

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use pocketmine\entity\Living;
use pocketmine\entity\Entity;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\entity\EntityEvent;
use pocketmine\network\mcpe\protocol\types\entity\EntityEventPacket;
use function mt_rand;

class Cow extends Living {

    public const NETWORK_ID = self::COW;

    protected float $width = 0.9;
    protected float $height = 1.4;

    private int $breedCooldown = 0;
    private int $eatCooldown = 0;

    public function __construct(World $world, CompoundTag $nbt){
        parent::__construct($world, $nbt);
        $this->setMaxHealth(10);
        $this->setHealth(10);
        $this->breedCooldown = 0;
        $this->eatCooldown = 0;
    }

    public function getName() : string{
        return "Cow";
    }

    protected function initEntity(CompoundTag $nbt) : void{
        parent::initEntity($nbt);
    }

    public function getDrops() : array{
        return [
            ItemFactory::getInstance()->get(ItemIds::RAW_BEEF, 0, mt_rand(1, 3)),
            ItemFactory::getInstance()->get(ItemIds::LEATHER, 0, mt_rand(0, 2))
        ];
    }

    public function onUpdate(int $currentTick) : bool {
        if ($this->isClosed() || !$this->isAlive()) {
            return false;
        }

        if ($this->breedCooldown > 0) {
            $this->breedCooldown--;
        }

        if ($this->eatCooldown > 0) {
            $this->eatCooldown--;
        }

        $this->moveRandomly();

        return parent::onUpdate($currentTick);
    }

    private function moveRandomly() : void {
        if(mt_rand(0, 20) === 0) { // Gerak setiap 20 ticks secara rata-rata
            $x = mt_rand(-1, 1);
            $y = mt_rand(-1, 1);
            $z = mt_rand(-1, 1);
            $this->move($x, $y, $z);
        }
    }

    public function interact(Player $player, Item $item) : bool {
        if ($item->getId() === ItemIds::WHEAT && $this->breedCooldown === 0) {
            $this->breed();
            return true;
        }

        if ($item->getId() === ItemIds::WHEAT && $this->eatCooldown === 0) {
            $this->eat();
            return true;
        }

        return parent::interact($player, $item);
    }

    private function breed() : void {
        $this->breedCooldown = 6000; // 5 menit cooldown
        $childNBT = new CompoundTag("", [
            "Pos" => [
                new DoubleTag("", $this->x),
                new DoubleTag("", $this->y),
                new DoubleTag("", $this->z)
            ],
            "Motion" => [
                new DoubleTag("", 0),
                new DoubleTag("", 0),
                new DoubleTag("", 0)
            ],
            "Rotation" => [
                new FloatTag("", $this->yaw),
                new FloatTag("", $this->pitch)
            ]
        ]);
        $child = new Cow($this->world, $childNBT);
        $child->spawnToAll();
    }

    private function eat() : void {
        $this->eatCooldown = 1200; // 1 menit cooldown
        $this->heal(2); // Heal 2 health points
        $this->broadcastEntityEvent(EntityEventPacket::EAT_GRASS_ANIMATION); // Eating animation
    }
}
