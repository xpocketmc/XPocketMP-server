<?php

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\entity\Animal;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use function atan2;
use function mt_rand;
use function sqrt;
use const M_PI;

class Sheep extends Animal{

    private int $breedingCooldown = 0;
    private bool $isBaby = false;
    private ?Vector3 $wanderTarget = null;

    public static function getNetworkTypeId() : string{ return EntityIds::SHEEP; }

    protected function getInitialSizeInfo() : EntitySizeInfo{ 
        return new EntitySizeInfo(1.3, 0.9);
    }

    public function initEntity(CompoundTag $nbt) : void{
        $this->setMaxHealth(8);
        parent::initEntity($nbt);
    }

    public function getName() : string{
        return "Sheep";
    }

    protected function entityBaseTick(int $tickDiff = 1) : bool{
        if($this->closed){
            return false;
        }

        $hasUpdate = parent::entityBaseTick($tickDiff);

        if($this->isAlive()){
            $this->randomMovement();
            $this->breedingCooldown--;
            if ($this->breedingCooldown < 0) {
                $this->breedingCooldown = 0;
            }

            $this->graze();
            $this->applyHealthRegen();
            $this->checkForWater();
            $this->processAmbientSounds();
            $this->handleDayNightCycle();
        }

        return $hasUpdate;
    }

    public function getDrops() : array{
        $woolCount = mt_rand(1, 3);
        $muttonCount = mt_rand(1, 2);

        return [
            VanillaItems::WOOL()->setCount($woolCount),
            VanillaItems::COOKED_MUTTON()->setCount($muttonCount)
        ];
    }

    private function randomMovement() : void{
        if ($this->wanderTarget === null || $this->distanceSquared($this->wanderTarget) < 1) {
            $this->wanderTarget = $this->generateRandomDirection()->addVector($this->location);
        }

        $direction = $this->wanderTarget->subtractVector($this->location)->normalize();
        $this->setMotion($direction->multiply(0.1));
    }

    private function generateRandomDirection() : Vector3{
        return new Vector3(
            mt_rand(-10, 10) / 10,
            0,
            mt_rand(-10, 10) / 10
        );
    }

    public function attack(EntityDamageEvent $source) : void{
        parent::attack($source);
        if($source->isCancelled()){
            return;
        }

        if($source instanceof EntityDamageByEntityEvent){
            $e = $source->getDamager();
            if($e !== null){
                $this->knockBackFromEntity($e);
            }
        }
    }

    private function knockBackFromEntity(Entity $entity) : void{
        $direction = $this->location->subtractVector($entity->location)->normalize();
        $this->setMotion($direction->multiply(0.5));
    }

    public function spawnTo(Player $player) : void{
        parent::spawnTo($player);
    }

    public function onInteract(Player $player, Vector3 $clickPos) : bool{
        if($this->isAlive()){
            $this->shear();
            return true;
        }
        return false;
    }

    private function shear() : void{
        $this->getWorld()->dropItem($this->location, VanillaItems::WOOL());
        $this->getWorld()->dropItem($this->location, VanillaItems::WOOL());
        $this->setNameTag("Sheared Sheep");
    }

    public function onUpdate(int $currentTick) : bool{
        $this->applyGravity();
        return parent::onUpdate($currentTick);
    }

    private function applyGravity() : void{
        if(!$this->isOnGround()){
            $this->motion->y -= 0.08;
        } else {
            $this->motion->y = 0;
        }
    }

    private function isOnGround() : bool{
        return $this->getWorld()->getBlockAt($this->location->floor()->subtract(0, 1, 0))->isSolid();
    }

    private function graze() : void{
        if(mt_rand(0, 100) < 5){
            $this->setNameTag("Grazing Sheep");
        }
    }

    protected function checkForIdleMovement() : void{
        if(mt_rand(0, 100) < 10){
            $direction = $this->generateRandomDirection();
            $this->setMotion($direction->multiply(0.1));
        }
    }

    public function setOnFire(int $seconds) : void{
        parent::setOnFire($seconds);
    }

    protected function applyHealthRegen() : void{
        if($this->isAlive() && mt_rand(0, 100) < 10){
            $this->setHealth($this->getHealth() + 1);
        }
    }

    protected function checkForWater() : void{
        if($this->isInWater()){
            $this->motion->y += 0.1;
        }
    }

    public function isInWater() : bool{
        return $this->getWorld()->getBlockAt($this->location)->isLiquid();
    }

    protected function processAmbientSounds() : void{
        if(mt_rand(0, 100) < 5){
            $this->broadcastEntityEvent(EntityEventPacket::SOUND_AMBIENT);
        }
    }

    protected function handleDayNightCycle() : void{
        if($this->getWorld()->getTime() % 24000 < 12000){
            $this->setNameTag("Daytime Sheep");
        } else {
            $this->setNameTag("Nighttime Sheep");
        }
    }

    public function isSheared() : bool{
        return $this->hasNameTag() && $this->getNameTag() === "Sheared Sheep";
    }

    public function saveNBT() : CompoundTag{
        $nbt = parent::saveNBT();
        $nbt->setInt("BreedingCooldown", $this->breedingCooldown);
        $nbt->setByte("IsBaby", $this->isBaby ? 1 : 0);
        return $nbt;
    }

    public function loadNBT(CompoundTag $nbt) : void{
        parent::loadNBT($nbt);
        $this->breedingCooldown = $nbt->getInt("BreedingCooldown", 0);
        $this->isBaby = $nbt->getByte("IsBaby", 0) === 1;
    }

    public function jump() : void{
        if($this->isOnGround()){
            $this->motion->y = 0.5;
        }
    }

    private function moveRandomly() : void{
        if(mt_rand(0, 100) < 10){
            $this->setMotion($this->generateRandomDirection()->multiply(0.2));
        }
    }

    private function avoidObstacles() : void{
        $blockInFront = $this->getWorld()->getBlockAt($this->location->add($this->getDirectionVector()));
        if(!$blockInFront->isPassable()){
            $this->setMotion($this->generateRandomDirection()->multiply(0.2));
        }
    }

    public function isBreedingItem(Item $item) : bool{
        return $item->getId() === VanillaItems::WHEAT()->getId();
    }

    public function onCollideWithEntity(Entity $entity) : void{
        parent::onCollideWithEntity($entity);
        $this->setMotion($this->generateRandomDirection()->multiply(0.2));
    }

    public function onDeath() : void{
        parent::onDeath();
        foreach($this->getDrops() as $drop){
            $this->getWorld()->dropItem($this->location, $drop);
        }
    }

    public function onDamaged(EntityDamageEvent $source) : void{
        parent::onDamaged($source);
        if($source->isCancelled()){
            return;
        }

        $this->setMotion($this->generateRandomDirection()->multiply(0.5));
    }

    public function onInteractWithPlayer(Player $player) : void{
        $this->setNameTag("Interacted with Player");
    }

    public function onTamedByPlayer(Player $player) : void{
        $this->setNameTag("Tamed by " . $player->getName());
    }

    public function onUnleashed() : void{
        $this->setNameTag("Unleashed Sheep");
    }

    public function onLeashed(Player $player) : void{
        $this->setNameTag("Leashed by " . $player->getName());
    }

    public function onBreed(Animal $partner) : void{
        parent::onBreed($partner);
        if($partner instanceof Sheep){
            $this->breedWith($partner);
        }
    }

    private function breedWith(Sheep $partner) : void{
        if($this->canBreed() && $partner->canBreed()){
            $baby = new Sheep($this->location);
            $baby->setBaby(true);
            $this->getWorld()->addEntity($baby);
            $this->breedingCooldown = 6000; // 5 minutes cooldown
            $partner->breedingCooldown = 6000; // 5 minutes cooldown
            $this->setNameTag("Bred with " . $partner->getName());
        }
    }

    public function setBaby(bool $isBaby) : void{
        $this->isBaby = $isBaby;
    }

    public function isBaby() : bool{
        return $this->isBaby;
    }

    public function onFleeFromEntity(Entity $entity) : void{
        $this->setNameTag("Fleeing from " . $entity->getName());
        $this->setMotion($this->generateRandomDirection()->multiply(0.3));
    }

    public function onApproachEntity(Entity $entity) : void{
        $this->setNameTag("Approaching " . $entity->getName());
    }

    public function onFeed(Item $item) : void{
        if($this->isBreedingItem($item)){
            $this->breed();
            $this->setNameTag("Fed with Breeding Item");
        } else {
            $this->setNameTag("Fed with Non-breeding Item");
        }
    }

    private function breed() : void{
        if($this->canBreed()){
            $this->breedingCooldown = 6000; // 5 minutes cooldown
        }
    }

    public function canBreed() : bool{
        return $this->breedingCooldown === 0;
    }
}
