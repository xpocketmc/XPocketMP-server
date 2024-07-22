<?php

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\entity\animation\AnimateEntityPacket;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\world\World;
use pocketmine\world\WorldManager;
use pocketmine\player\Player;
use function atan2;
use function mt_rand;
use function sqrt;
use const M_PI;

class Salmon extends WaterAnimal {

    public static function getNetworkTypeId() : string{ return EntityIds::SALMON; }

    public ?Vector3 $swimDirection = null;
    public float $swimSpeed = 0.1;

    private int $switchDirectionTicker = 0;
    private int $reproduceTicker = 0;

    protected function getInitialSizeInfo() : EntitySizeInfo{ return new EntitySizeInfo(0.75, 0.75); }

    public function initEntity(CompoundTag $nbt) : void{
        $this->setMaxHealth(3);
        parent::initEntity($nbt);
    }

    public function getName() : string{
        return "Salmon";
    }

    public function attack(EntityDamageEvent $source) : void{
        parent::attack($source);
        if($source->isCancelled()){
            return;
        }

        if($source instanceof EntityDamageByEntityEvent){
            $this->swimSpeed = mt_rand(150, 350) / 2000;
            $e = $source->getDamager();
            if($e !== null){
                $this->swimDirection = $this->location->subtractVector($e->location)->normalize();
            }

            $this->broadcastAnimation();
        }
    }

    private function generateRandomDirection() : Vector3{
        return new Vector3(mt_rand(-1000, 1000) / 1000, mt_rand(-500, 500) / 1000, mt_rand(-1000, 1000) / 1000);
    }

    protected function entityBaseTick(int $tickDiff = 1) : bool{
        if($this->closed){
            return false;
        }

        if(++$this->switchDirectionTicker === 100){
            $this->switchDirectionTicker = 0;
            if(mt_rand(0, 100) < 50){
                $this->swimDirection = null;
            }
        }

        if(++$this->reproduceTicker === 6000){ // Every 5 minutes
            $this->reproduceTicker = 0;
            $this->spawnOffspring();
        }

        $hasUpdate = parent::entityBaseTick($tickDiff);

        if($this->isAlive()){

            if($this->location->y > 62 && $this->swimDirection !== null){
                $this->swimDirection = $this->swimDirection->withComponents(null, -0.5, null);
            }

            $inWater = $this->isUnderwater();
            $this->setHasGravity(!$inWater);
            if(!$inWater){
                $this->swimDirection = null;
            }elseif($this->swimDirection !== null){
                if($this->motion->lengthSquared() <= $this->swimDirection->lengthSquared()){
                    $this->motion = $this->swimDirection->multiply($this->swimSpeed);
                }
            }else{
                $this->swimDirection = $this->generateRandomDirection();
                $this->swimSpeed = mt_rand(50, 100) / 2000;
            }

            $f = sqrt(($this->motion->x ** 2) + ($this->motion->z ** 2));
            $this->setRotation(
                -atan2($this->motion->x, $this->motion->z) * 180 / M_PI,
                -atan2($f, $this->motion->y) * 180 / M_PI
            );

            $this->broadcastAnimation(); // Broadcast animation while moving
        }

        return $hasUpdate;
    }

    private function broadcastAnimation() : void{
        $packet = new AnimateEntityPacket();
        $packet->entityRuntimeId = $this->getId();
        $packet->animation = "minecraft:swim";
        $this->getWorld()->broadcastPacketToViewers($this->getPosition(), $packet);
    }

    private function spawnOffspring() : void{
        $world = $this->getWorld();
        if($world instanceof World){
            $nbt = Entity::createBaseNBT($this->getPosition());
            $salmon = new Salmon(EntityDataHelper::parseLocation($nbt, $world), $nbt);
            $salmon->spawnToAll();
        }
    }

    public function getDrops() : array{
        return [
            VanillaItems::RAW_SALMON()->setCount(mt_rand(1, 3))
        ];
    }
}
