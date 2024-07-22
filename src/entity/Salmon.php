<?php

namespace pocketmine\entity;

use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\math\Vector3;
use pocketmine\level\particle\BubbleParticle;

class Salmon extends WaterAnimal {

    public const NETWORK_ID = self::SALMON;

    public $width = 0.7;
    public $height = 0.4;

    private $swimDirection;
    private $changeDirectionTicks = 0;

    public function __construct(Level $level, CompoundTag $nbt) {
        parent::__construct($level, $nbt);
        $this->setMaxHealth(3);
        $this->setHealth($this->getMaxHealth());
        $this->swimDirection = new Vector3(0, 0, 0);
    }

    public function getName(): string {
        return "Salmon";
    }

    public function initEntity(): void {
        parent::initEntity();
        $this->setGenericFlag(self::DATA_FLAG_IMMOBILE, false);
    }

    public function getDrops(): array {
        return [
            Item::get(Item::RAW_SALMON, 0, 1)
        ];
    }

    public function getSpeed(): float {
        return 1.2;
    }

    protected function entityBaseTick(int $tickDiff = 1): bool {
        $hasUpdate = parent::entityBaseTick($tickDiff);

        // Change direction every 100 ticks (5 seconds)
        if ($this->changeDirectionTicks-- <= 0) {
            $this->changeSwimDirection();
            $this->changeDirectionTicks = 100;
        }

        // Move in the current swim direction
        $this->move($this->swimDirection->x, $this->swimDirection->y, $this->swimDirection->z);
        
        // Play bubble particles while moving
        $this->level->addParticle(new BubbleParticle($this->add(0, 0.5, 0)));

        return $hasUpdate;
    }

    private function changeSwimDirection(): void {
        $this->swimDirection = new Vector3(
            mt_rand(-10, 10) / 10,
            mt_rand(-5, 5) / 10,
            mt_rand(-10, 10) / 10
        );
    }

    public function updateMovement(): void {
        parent::updateMovement();
    }
}
