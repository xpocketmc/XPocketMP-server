<?php

namespace pocketmine\item;

class Leather extends Item {
    /**
     * Leather constructor.
     *
     * @param int $meta
     */
    public function __construct(int $meta = 0){
        parent::__construct(self::LEATHER, $meta, "Leather");
    }

    /**
     * Returns the maximum stack size for this item.
     *
     * @return int
     */
    public function getMaxStackSize(): int {
        return 64;
    }

    /**
     * Returns whether this item can be used as a fuel in furnaces.
     *
     * @return bool
     */
    public function canBeUsedAsFuel(): bool {
        return false;
    }

    /**
     * Returns the fuel time for this item if it can be used as fuel.
     *
     * @return int
     */
    public function getFuelTime(): int {
        return 0;
    }

    /**
     * Determines if this item is consumable.
     *
     * @return bool
     */
    public function isConsumable(): bool {
        return false;
    }
}
