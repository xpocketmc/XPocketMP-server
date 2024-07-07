<?php

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\scheduler\Task;
use function mt_rand;

class Cow extends Living
{
    public const NETWORK_ID = EntityIds::COW;
    private const MOVE_SPEED = 0.1;

    private ?Player $targetPlayer = null;

    public function __construct(Location $location, CompoundTag $nbt)
    {
        parent::__construct($location, $nbt);
        $this->scheduleAI();
    }

    public function getName() : string
    {
        return "Cow";
    }

    public function getDrops() : array
    {
        return [
            Item::get(ItemIds::RAW_BEEF, 0, mt_rand(1, 3)), // Drop 1-3 raw beef
            Item::get(ItemIds::LEATHER, 0, mt_rand(0, 2))  // Drop 0-2 leather
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

        $this->server->broadcastPackets($this->getViewers(), [$pk]);
    }

    public function getInitialSizeInfo() : EntitySizeInfo
    {
        return new EntitySizeInfo(1.4, 0.9); // tinggi 1.4 unit, lebar 0.9 unit
    }

    public static function getNetworkTypeId() : string
    {
        return EntityIds::COW;
    }

    private function scheduleAI() : void
    {
        Server::getInstance()->getScheduler()->scheduleRepeatingTask(new class($this) extends Task {
            private Cow $cow;

            public function __construct(Cow $cow)
            {
                $this->cow = $cow;
            }

            public function onRun() : void
            {
                $this->cow->performAI();
            }
        }, 20); // 20 ticks = 1 second
    }

    public function performAI() : void
    {
        if ($this->isOnGround()) {
            $this->moveRandomly();
        }

        // Jump if there is a block in front
        if ($this->isBlockInFront()) {
            $this->jump();
        }
    }

    private function moveRandomly() : void
    {
        $direction = mt_rand(0, 360);
        $this->yaw = $direction;
        $this->moveForward(self::MOVE_SPEED);
    }

    private function isBlockInFront() : bool
    {
        $front = $this->getPosition()->add($this->getDirectionVector()->multiply(1));
        return !$this->level->getBlock($front)->isSolid();
    }

    private function jump() : void
    {
        $this->motion->y = 0.5; // Jump strength
    }

    private function moveForward(float $speed) : void
    {
        $directionVector = $this->getDirectionVector()->multiply($speed);
        $this->motion->x = $directionVector->x;
        $this->motion->z = $directionVector->z;
        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
    }

    public function onInteract(Player $player, Item $item) : bool
    {
        // Feed the cow
        if ($item->getId() === ItemIds::WHEAT) {
            $this->feed($player);
            return true;
        }

        return parent::onInteract($player, $item);
    }

    private function feed(Player $player) : void
    {
        // Logic for feeding the cow
        // For simplicity, just set the target player to follow
        $this->targetPlayer = $player;
        $player->sendMessage("You fed the cow!");
    }

    public function onUpdate(int $currentTick) : bool
    {
        parent::onUpdate($currentTick);

        if ($this->targetPlayer !== null) {
            $this->followPlayer();
        }

        return true;
    }

    private function followPlayer() : void
    {
        $directionVector = $this->targetPlayer->getPosition()->subtract($this->getPosition())->normalize();
        $this->motion->x = $directionVector->x * self::MOVE_SPEED;
        $this->motion->z = $directionVector->z * self::MOVE_SPEED;
        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
        $this->yaw = atan2($this->motion->z, $this->motion->x) * 180 / M_PI - 90;
    }
}
