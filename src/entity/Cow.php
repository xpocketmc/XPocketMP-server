<?php

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\Item;
use pocketmine\item\ItemTypeIds;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\particle\HeartParticle;
use pocketmine\world\sound\CowMooSound;
use pocketmine\world\sound\CowHurtSound;
use pocketmine\world\sound\CowDeathSound;

class Cow extends Living{

    private bool $isBreeding = false;

    protected function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo(1.4, 0.9);
    }

    public static function getNetworkTypeId(): string{
        return EntityIds::COW;
    }

    public function getName(): string{
        return "Cow";
    }

    protected function onTick(int $currentTick): void{
        parent::onTick($currentTick);

        if(mt_rand(1, 200) === 1){
            $this->getWorld()->addSound($this->getPosition(), new CowMooSound());
        }
    }

    protected function playHurtSound(): void{
        $this->getWorld()->addSound($this->getPosition(), new CowHurtSound());
    }

    protected function playDeathSound(): void{
        $this->getWorld()->addSound($this->getPosition(), new CowDeathSound());
    }

    protected function dropItemsOnDeath(): void{
        $world = $this->getWorld();
        $world->dropItem($this->getPosition(), ItemTypeIds::RAW_BEEF, mt_rand(1, 3));
        $world->dropItem($this->getPosition(), ItemTypeIds::LEATHER, mt_rand(0, 2));
    }

    public function onEatButtonPressed(Vector3 $playerPosition, Item $item): void{
        if($item->getTypeId() === ItemTypeIds::WHEAT){
            $this->moveTowards($playerPosition);
            $this->eatWheat();
            $item->pop();
        }
    }

    protected function eatWheat(): void{
        $this->setHealth(min($this->getMaxHealth(), $this->getHealth() + 2));
        $this->getWorld()->addParticle($this->getPosition(), new HeartParticle());
    }

    protected function moveTowards(Vector3 $targetPosition): void{
        $direction = $targetPosition->subtract($this->getPosition())->normalize();
        $speed = 0.1;
        $this->motion->setComponents(
            $direction->x * $speed,
            $this->motion->y,
            $direction->z * $speed
        );
        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
    }

    public function breedWith(Entity $partner): void{
        if($this->isBreeding || !$partner instanceof Cow || $this->isBaby() || $partner->isBaby()){
            return;
        }

        $this->isBreeding = true;
        $partner->isBreeding = true;

        $this->getWorld()->addParticle($this->getPosition(), new HeartParticle());
        $this->getWorld()->addParticle($partner->getPosition(), new HeartParticle());

        $this->getWorld()->scheduleDelayedTask(function() use ($partner): void{
            $babyPosition = $this->getPosition()->add(0.5, 0, 0.5);
            $baby = new Cow($this->getWorld(), $babyPosition);
            $baby->spawnToAll();

            $this->isBreeding = false;
            $partner->isBreeding = false;
        }, 100);

        $this->scheduleBreedingCooldown();
        $partner->scheduleBreedingCooldown();
    }

    private function scheduleBreedingCooldown(): void{
        $this->isBreeding = true;
        $this->getWorld()->scheduleDelayedTask(function(): void{
            $this->isBreeding = false;
        }, 600);
    }
}
