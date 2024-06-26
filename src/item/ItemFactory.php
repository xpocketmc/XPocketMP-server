<?php

namespace pocketmine\item;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory as PMItemFactory;

class ItemFactory {

    /** @var array */
    private static $customItems = [];

    /**
     * Registers a custom item.
     *
     * @param string $class
     */
    public static function registerItem(string $class): void {
        if (is_subclass_of($class, Item::class)) {
            $item = new $class();
            self::$customItems[$item->getId()] = $class;
            PMItemFactory::registerItem($item);
        }
    }

    /**
     * Initializes basic items.
     */
    public static function initItems(): void {
        // Initialize Leather item
        self::registerItem(Leather::class);
    }

    /**
     * Retrieves a custom item by its ID.
     *
     * @param int $id
     * @return Item|null
     */
    public static function get(int $id): ?Item {
        if (isset(self::$customItems[$id])) {
            $class = self::$customItems[$id];
            return new $class();
        }
        return null;
    }

    /**
     * Method to get an instance of ItemFactory.
     *
     * @return self
     */
    public static function getInstance(): self {
        return new self();
    }

    /**
     * Run phpstan analyze command.
     *
     * @return void
     */
    public static function runPhpStan(): void {
        $command = "./vendor/bin/phpstan analyze --no-progress --memory-limit=2G";
        echo shell_exec($command);
    }
}

// Initialize basic items on file load
ItemFactory::initItems();
