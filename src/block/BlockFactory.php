<?php

namespace pocketmine\block;

class BlockFactory {
    private static ?BlockFactory $instance = null;

    /** @var Block[] */
    private array $blocks = [];

    /** @var Item[] */
    private array $items = [];

    private function __construct() {
        // Daftarkan semua blok yang diperlukan di sini
        $this->registerBlock(new Block(BlockIds::WHEAT_BLOCK, "Wheat Block"));
        
        // Daftarkan semua item yang diperlukan di sini
        $this->registerItem(new Item(ItemIds::WHEAT_ITEM, "Wheat"));
        $this->registerItem(new Item(ItemIds::CARROT_ITEM, "Carrot"));
        $this->registerItem(new Item(ItemIds::POTATO_ITEM, "Potato"));
        $this->registerItem(new Item(ItemIds::APPLE_ITEM, "Apple"));
        // Tambahkan lebih banyak item sesuai kebutuhan Anda
    }

    public static function getInstance(): BlockFactory {
        if (self::$instance === null) {
            self::$instance = new BlockFactory();
        }
        return self::$instance;
    }

    private function registerBlock(Block $block): void {
        $this->blocks[$block->getId()] = $block;
    }

    private function registerItem(Item $item): void {
        $this->items[$item->getId()] = $item;
    }

    public function getBlock(int $id): ?Block {
        return $this->blocks[$id] ?? null;
    }

    public function getItem(int $id): ?Item {
        return $this->items[$id] ?? null;
    }
}

class Block {
    private int $id;
    private string $name;

    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }
}

class Item {
    private int $id;
    private string $name;

    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }
}

class BlockIds {
    public const WHEAT_BLOCK = 59;
    // Tambahkan ID blok lainnya di sini sesuai kebutuhan
}

class ItemIds {
    public const WHEAT_ITEM = 296;
    public const CARROT_ITEM = 391;
    public const POTATO_ITEM = 392;
    public const APPLE_ITEM = 260;
    // Tambahkan ID item lainnya di sini sesuai kebutuhan
}
