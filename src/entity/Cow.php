<?php

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\item\ItemFactory;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\entity\animation\EntityAnimation;
use pocketmine\entity\animation\JumpAnimation;
use pocketmine\world\sound\GenericSound;
use pocketmine\entity\animation\DeathAnimation;
use pocketmine\entity\animation\HurtAnimation;
use pocketmine\entity\animation\WalkAnimation;
use pocketmine\entity\Location;
use pocketmine\entity\movement\WalkingMovement;

class Cow extends Animal {

    public static function getNetworkTypeId(): string {
        return "minecraft:cow";
    }

    protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(1.3, 0.9); // sesuai dengan Bedrock Edition
    }

    public function getName(): string {
        return "Cow";
    }

    public function getDrops(): array {
        $drops = [
            ItemFactory::getInstance()->get(Item::RAW_BEEF, 0, mt_rand(1, 3)),
            ItemFactory::getInstance()->get(Item::LEATHER, 0, mt_rand(0, 2))
        ];

        if ($this->isOnFire()) {
            $drops[0] = ItemFactory::getInstance()->get(Item::COOKED_BEEF, 0, mt_rand(1, 3));
        }

        return $drops;
    }

    protected function initEntity(CompoundTag $nbt): void {
        parent::initEntity($nbt);
        $this->setMovementType(new WalkingMovement($this, 0.25));
    }

    public function interact(Player $player, Item $item, Vector3 $clickPos): bool {
        if ($item->getId() === Item::BUCKET) {
            // Milk the cow
            $item->decrementCount();
            $player->getInventory()->addItem(ItemFactory::getInstance()->get(Item::MILK_BUCKET, 1));
            $this->getWorld()->addSound($this->location, new GenericSound($this->location, "entity.cow.milk"));
            return true;
        }
        return parent::interact($player, $item, $clickPos);
    }

    public function attack(EntityDamageEvent $source): void {
        parent::attack($source);
        $this->getWorld()->addSound($this->location, new GenericSound($this->location, "entity.cow.hurt"));
        $this->broadcastAnimation(new HurtAnimation($this));
    }

    public function kill(): void {
        parent::kill();
        $this->getWorld()->addSound($this->location, new GenericSound($this->location, "entity.cow.death"));
        $this->broadcastAnimation(new DeathAnimation($this));
    }

    protected function onUpdate(int $currentTick): bool {
        if ($this->isClosed()) {
            return false;
        }

        $this->broadcastAnimation(new WalkAnimation($this));

        return parent::onUpdate($currentTick);
    }
}
