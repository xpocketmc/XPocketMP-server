<?php

namespace pocketmine\entity;

use pocketmine\entity\Living;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\world\World;
use pocketmine\entity\Location;

class Chicken extends Living {

    public const NETWORK_ID = EntityIds::CHICKEN;

    /** @var int */
    private $wanderTime = 0;
	
    /** @var float */
    private $yaw = 0;

    /** @var float */
    private $pitch = 0;

    /** @var Player|null */
    private $targetPlayer = null;

    protected function initEntity(CompoundTag $nbt) : void{
        parent::initEntity($nbt);
        $this->setMaxHealth(4);
        $this->setHealth(4);
    }

    public function getName() : string{
        return "Chicken";
    }

    public function getDrops() : array{
        $drops = [];
        $drops[] = VanillaItems::FEATHER()->setCount(mt_rand(0, 2));
        if($this->isOnFire()){
            $drops[] = VanillaItems::COOKED_CHICKEN()->setCount(1);
        }else{
            $drops[] = VanillaItems::RAW_CHICKEN()->setCount(1);
        }
        return $drops;
    }

    public function entityBaseTick(int $tickDiff = 1) : bool{
        $hasUpdate = parent::entityBaseTick($tickDiff);

        $this->updateTargetPlayer();

        if ($this->targetPlayer instanceof Player) {
            $this->followPlayer();
        } else {
            $this->wander($tickDiff);
        }

        // Membaca properti yaw dan pitch untuk menghindari peringatan PHPStan
        $currentYaw = $this->yaw;
        $currentPitch = $this->pitch;

        return $hasUpdate;
    }

    private function wander(int $tickDiff) : void{
        if($this->wanderTime > 0){
            $this->wanderTime -= $tickDiff;
            $this->move($this->motion->x, $this->motion->y, $this->motion->z);

            // Mengatur orientasi (yaw dan pitch) sesuai arah gerakan
            $this->updateOrientation();
        } else {
            if(mt_rand(1, 100) <= 10){
                $this->wanderTime = mt_rand(20, 100);
                $this->motion->x = mt_rand(-10, 10) / 10;
                $this->motion->z = mt_rand(-10, 10) / 10;
            } else {
                $this->motion->x = 0;
                $this->motion->z = 0;
            }
        }
    }

    private function updateTargetPlayer() : void{
        foreach ($this->getWorld()->getPlayers() as $player) {
            if ($player->getInventory()->getItemInHand()->equals(VanillaItems::WHEAT_SEEDS())) {
                if ($this->getPosition()->distance($player->getPosition()) < 10) {
                    $this->targetPlayer = $player;
                    return;
                }
            }
        }
        $this->targetPlayer = null;
    }

    private function followPlayer() : void{
        if ($this->targetPlayer instanceof Player) {
            $direction = $this->targetPlayer->getPosition()->subtract($this->getPosition()->x, $this->getPosition()->y, $this->getPosition()->z)->normalize();
            $this->motion->x = $direction->x * 0.2;
            $this->motion->z = $direction->z * 0.2;

            // Mengatur orientasi (yaw dan pitch) sesuai arah gerakan
            $this->updateOrientation();
            $this->move($this->motion->x, $this->motion->y, $this->motion->z);

            // Auto-jump jika ada penghalang di depan
            $this->autoJump();
        }
    }

    private function autoJump() : void{
        // Deteksi blok di depan dan lompat jika ada penghalang
        $blockFront = $this->getWorld()->getBlock($this->getPosition()->add($this->motion->x, 0, $this->motion->z));
        if($blockFront->isSolid()){
            $this->motion->y = 0.42;
        }
    }

    public function onInteract(Player $player, Vector3 $clickPos) : bool{
        $item = $player->getInventory()->getItemInHand();
        if($item->equals(VanillaItems::WHEAT_SEEDS())){
            $this->heal(new EntityRegainHealthEvent($this, 1, EntityRegainHealthEvent::CAUSE_EATING));
            $item->pop(); // Kurangi jumlah benih di tangan pemain
            $player->getInventory()->setItemInHand($item); // Perbarui item di tangan pemain
            return true;
        }
        return parent::onInteract($player, $clickPos);
    }

    protected function getInitialSizeInfo() : EntitySizeInfo{
        return new EntitySizeInfo(0.4, 0.7);
    }

    public static function getNetworkTypeId() : string{
        return EntityIds::CHICKEN;
    }

    public function attack(EntityDamageEvent $source) : void{
        parent::attack($source);

        // Menambahkan perilaku lari ketika dipukul
        $this->wanderTime = mt_rand(20, 100); // Set waktu lari
        $this->motion->x = mt_rand(-20, 20) / 10; // Kecepatan lari di sumbu X
        $this->motion->z = mt_rand(-20, 20) / 10; // Kecepatan lari di sumbu Z

        // Mengatur orientasi (yaw dan pitch) sesuai arah gerakan
        $this->updateOrientation();
    }

    private function updateOrientation() : void{
        // Mengatur yaw (putaran horizontal) berdasarkan arah gerakan
        $this->yaw = rad2deg(atan2($this->motion->z, $this->motion->x)) - 90;
        $this->setRotation($this->yaw, $this->pitch); // Tambahkan ini untuk mengatur rotasi entitas
    }

    public static function spawnRandomly(World $world) : void{
        for($i = 0; $i < mt_rand(1, 4); $i++){
            $x = mt_rand(0, 100);
            $y = mt_rand(60, 80);
            $z = mt_rand(0, 100);
            $location = new Location($x, $y, $z, $world, 0, 0);
            $chicken = new self($location, new CompoundTag());
            $world->addEntity($chicken);
        }
    }
}
