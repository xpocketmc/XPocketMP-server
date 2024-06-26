<?php

namespace pocketmine\item;

use pocketmine\item\Item;

class ItemFactory {

    /** @var array */
    private static $customItems = [];

    /**
     * Registers a custom item.
     *
     * @param string $id
     * @param callable $callback
     */
    public static function registerItem(string $id, callable $callback): void {
        self::$customItems[$id] = $callback;
    }

    /**
     * Initializes basic items.
     */
    public static function initItems(): void {
        // Initialize Leather item
        self::registerItem("leather", function() {
            return Item::get(Item::LEATHER);
        });
        // Add more items as needed
    }

    /**
     * Retrieves a custom item by its ID.
     *
     * @param string $id
     * @return Item|null
     */
    public static function get(string $id): ?Item {
        if (isset(self::$customItems[$id])) {
            $callback = self::$customItems[$id];
            return $callback();
        }
        return null;
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
