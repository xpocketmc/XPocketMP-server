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
        $this->registerBlock(new Block(new BlockIdentifier(BlockIds::STONE, 0), BlockIds::STONE, "Stone"));
        $this->registerBlock(new Block(new BlockIdentifier(BlockIds::DIRT, 0), BlockIds::DIRT, "Dirt"));
        $this->registerBlock(new Block(new BlockIdentifier(BlockIds::WOOD, 0), BlockIds::WOOD, "Wood"));
        $this->registerBlock(new Block(new BlockIdentifier(BlockIds::GLASS, 0), BlockIds::GLASS, "Glass"));
        
        // Daftarkan semua item yang diperlukan di sini
        $this->registerItem(new Item(ItemIds::IRON_SHOVEL, "Iron Shovel"));
        $this->registerItem(new Item(ItemIds::IRON_PICKAXE, "Iron Pickaxe"));
        $this->registerItem(new Item(ItemIds::IRON_AXE, "Iron Axe"));
        $this->registerItem(new Item(ItemIds::IRON_HOE, "Iron Hoe"));
        $this->registerItem(new Item(ItemIds::IRON_SWORD, "Iron Sword"));
        $this->registerItem(new Item(ItemIds::GOLD_SHOVEL, "Gold Shovel"));
        $this->registerItem(new Item(ItemIds::GOLD_PICKAXE, "Gold Pickaxe"));
        $this->registerItem(new Item(ItemIds::GOLD_AXE, "Gold Axe"));
        $this->registerItem(new Item(ItemIds::GOLD_HOE, "Gold Hoe"));
        $this->registerItem(new Item(ItemIds::GOLD_SWORD, "Gold Sword"));
        $this->registerItem(new Item(ItemIds::DIAMOND_SHOVEL, "Diamond Shovel"));
        $this->registerItem(new Item(ItemIds::DIAMOND_PICKAXE, "Diamond Pickaxe"));
        $this->registerItem(new Item(ItemIds::DIAMOND_AXE, "Diamond Axe"));
        $this->registerItem(new Item(ItemIds::DIAMOND_HOE, "Diamond Hoe"));
        $this->registerItem(new Item(ItemIds::DIAMOND_SWORD, "Diamond Sword"));
        $this->registerItem(new Item(ItemIds::WOODEN_SHOVEL, "Wooden Shovel"));
        $this->registerItem(new Item(ItemIds::WOODEN_PICKAXE, "Wooden Pickaxe"));
        $this->registerItem(new Item(ItemIds::WOODEN_AXE, "Wooden Axe"));
        $this->registerItem(new Item(ItemIds::WOODEN_HOE, "Wooden Hoe"));
        $this->registerItem(new Item(ItemIds::WOODEN_SWORD, "Wooden Sword"));
        $this->registerItem(new Item(ItemIds::STONE_SHOVEL, "Stone Shovel"));
        $this->registerItem(new Item(ItemIds::STONE_PICKAXE, "Stone Pickaxe"));
        $this->registerItem(new Item(ItemIds::STONE_AXE, "Stone Axe"));
        $this->registerItem(new Item(ItemIds::STONE_HOE, "Stone Hoe"));
        $this->registerItem(new Item(ItemIds::STONE_SWORD, "Stone Sword"));
        $this->registerItem(new Item(ItemIds::GOLDEN_APPLE, "Golden Apple"));
        $this->registerItem(new Item(ItemIds::APPLE, "Apple"));
        $this->registerItem(new Item(ItemIds::COOKED_BEEF, "Cooked Beef"));
        $this->registerItem(new Item(ItemIds::BREAD, "Bread"));
        $this->registerItem(new Item(ItemIds::COOKED_PORKCHOP, "Cooked Porkchop"));
        $this->registerItem(new Item(ItemIds::COOKED_CHICKEN, "Cooked Chicken"));
        $this->registerItem(new Item(ItemIds::BOW, "Bow"));
        $this->registerItem(new Item(ItemIds::ARROW, "Arrow"));
        $this->registerItem(new Item(ItemIds::BONE, "Bone"));
        $this->registerItem(new Item(ItemIds::SADDLE, "Saddle"));
        $this->registerItem(new Item(ItemIds::LEATHER, "Leather"));
        $this->registerItem(new Item(ItemIds::SUGAR, "Sugar"));
        $this->registerItem(new Item(ItemIds::EGG, "Egg"));
        $this->registerItem(new Item(ItemIds::FEATHER, "Feather"));
        $this->registerItem(new Item(ItemIds::POTATO, "Potato"));
        $this->registerItem(new Item(ItemIds::CARROT, "Carrot"));
        $this->registerItem(new Item(ItemIds::PUMPKIN_PIE, "Pumpkin Pie"));
        $this->registerItem(new Item(ItemIds::COOKED_COD, "Cooked Cod"));
        $this->registerItem(new Item(ItemIds::COOKED_SALMON, "Cooked Salmon"));
        $this->registerItem(new Item(ItemIds::COOKIE, "Cookie"));
        $this->registerItem(new Item(ItemIds::MELON, "Melon"));
        $this->registerItem(new Item(ItemIds::PAPER, "Paper"));
        $this->registerItem(new Item(ItemIds::BOOK, "Book"));
        $this->registerItem(new Item(ItemIds::EMERALD, "Emerald"));
        $this->registerItem(new Item(ItemIds::DIAMOND, "Diamond"));
        $this->registerItem(new Item(ItemIds::GOLD_INGOT, "Gold Ingot"));
        $this->registerItem(new Item(ItemIds::IRON_INGOT, "Iron Ingot"));
        $this->registerItem(new Item(ItemIds::COAL, "Coal"));
        $this->registerItem(new Item(ItemIds::STICK, "Stick"));
        $this->registerItem(new Item(ItemIds::STRING, "String"));
        $this->registerItem(new Item(ItemIds::BLAZE_ROD, "Blaze Rod"));
        $this->registerItem(new Item(ItemIds::GHAST_TEAR, "Ghast Tear"));
        $this->registerItem(new Item(ItemIds::GLOWSTONE_DUST, "Glowstone Dust"));
        $this->registerItem(new Item(ItemIds::ENDER_PEARL, "Ender Pearl"));
        $this->registerItem(new Item(ItemIds::IRON_NUGGET, "Iron Nugget"));
        $this->registerItem(new Item(ItemIds::GOLD_NUGGET, "Gold Nugget"));
        $this->registerItem(new Item(ItemIds::DIAMOND_HORSE_ARMOR, "Diamond Horse Armor"));
        $this->registerItem(new Item(ItemIds::GOLDEN_HORSE_ARMOR, "Golden Horse Armor"));
        $this->registerItem(new Item(ItemIds::IRON_HORSE_ARMOR, "Iron Horse Armor"));
        $this->registerItem(new Item(ItemIds::LEATHER_HORSE_ARMOR, "Leather Horse Armor"));
        $this->registerItem(new Item(ItemIds::MAGMA_CREAM, "Magma Cream"));
        $this->registerItem(new Item(ItemIds::NETHERRACK, "Netherrack"));
        $this->registerItem(new Item(ItemIds::NETHER_BRICK, "Nether Brick"));
        $this->registerItem(new Item(ItemIds::NETHER_QUARTZ, "Nether Quartz"));
        $this->registerItem(new Item(ItemIds::PRISMARINE_SHARD, "Prismarine Shard"));
        $this->registerItem(new Item(ItemIds::PRISMARINE_CRYSTALS, "Prismarine Crystals"));
        $this->registerItem(new Item(ItemIds::RABBIT_HIDE, "Rabbit Hide"));
        $this->registerItem(new Item(ItemIds::RABBIT_FOOT, "Rabbit Foot"));
        $this->registerItem(new Item(ItemIds::RABBIT_STEW, "Rabbit Stew"));
        $this->registerItem(new Item(ItemIds::RAW_BEEF, "Raw Beef"));
        $this->registerItem(new Item(ItemIds::RAW_CHICKEN, "Raw Chicken"));
        $this->registerItem(new Item(ItemIds::RAW_FISH, "Raw Fish"));
        $this->registerItem(new Item(ItemIds::RAW_PORKCHOP, "Raw Porkchop"));
        $this->registerItem(new Item(ItemIds::SPECKLED_MELON, "Speckled Melon"));
        $this->registerItem(new Item(ItemIds::SPIDER_EYE, "Spider Eye"));
        $this->registerItem(new Item(ItemIds::STRING, "String"));
        $this->registerItem(new Item(ItemIds::TOTEM, "Totem of Undying"));
        $this->registerItem(new Item(ItemIds::TRIDENT, "Trident"));
        $this->registerItem(new Item(ItemIds::TURTLE_EGG, "Turtle Egg"));
        $this->registerItem(new Item(ItemIds::WHEAT, "Wheat"));
        $this->registerItem(new Item(ItemIds::WRITABLE_BOOK, "Writable Book"));
        $this->registerItem(new Item(ItemIds::WRITTEN_BOOK, "Written Book"));
        $this->registerItem(new Item(ItemIds::EXPERIENCE_BOTTLE, "Bottle o' Enchanting"));
        $this->registerItem(new Item(ItemIds::ENDER_CHEST, "Ender Chest"));
        $this->registerItem(new Item(ItemIds::ENCHANTED_BOOK, "Enchanted Book"));
        $this->registerItem(new Item(ItemIds::ENDER_EYE, "Eye of Ender"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_13, "Music Disc - 13"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_CAT, "Music Disc - Cat"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_BLOCKS, "Music Disc - Blocks"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_CHIRP, "Music Disc - Chirp"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_FAR, "Music Disc - Far"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_MALL, "Music Disc - Mall"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_MELLOHI, "Music Disc - Mellohi"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_STAL, "Music Disc - Stal"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_STRAD, "Music Disc - Strad"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_WAIT, "Music Disc - Wait"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_WARD, "Music Disc - Ward"));
        $this->registerItem(new Item(ItemIds::ENCHANTED_GOLDEN_APPLE, "Enchanted Golden Apple"));
        $this->registerItem(new Item(ItemIds::CARROT_ON_A_STICK, "Carrot on a Stick"));
        $this->registerItem(new Item(ItemIds::POPPED_CHORUS_FRUIT, "Popped Chorus Fruit"));
        $this->registerItem(new Item(ItemIds::CHORUS_FRUIT, "Chorus Fruit"));
        $this->registerItem(new Item(ItemIds::CHORUS_FLOWER, "Chorus Flower"));
        $this->registerItem(new Item(ItemIds::DRAGON_BREATH, "Dragon's Breath"));
        $this->registerItem(new Item(ItemIds::ELYTRA, "Elytra"));
        $this->registerItem(new Item(ItemIds::END_CRYSTAL, "End Crystal"));
        $this->registerItem(new Item(ItemIds::ELYTRA, "Elytra"));
        $this->registerItem(new Item(ItemIds::GHAST_TEAR, "Ghast Tear"));
        $this->registerItem(new Item(ItemIds::GLISTERING_MELON, "Glistering Melon"));
        $this->registerItem(new Item(ItemIds::GOLDEN_CARROT, "Golden Carrot"));
        $this->registerItem(new Item(ItemIds::INK_SAC, "Ink Sac"));
        $this->registerItem(new Item(ItemIds::LAPIS_LAZULI, "Lapis Lazuli"));
        $this->registerItem(new Item(ItemIds::MAGMA_CREAM, "Magma Cream"));
        $this->registerItem(new Item(ItemIds::MELON_SEEDS, "Melon Seeds"));
        $this->registerItem(new Item(ItemIds::NAME_TAG, "Name Tag"));
        $this->registerItem(new Item(ItemIds::NETHER_STAR, "Nether Star"));
        $this->registerItem(new Item(ItemIds::PHANTOM_MEMBRANE, "Phantom Membrane"));
        $this->registerItem(new Item(ItemIds::POPPED_CHORUS_FRUIT, "Popped Chorus Fruit"));
        $this->registerItem(new Item(ItemIds::PRISMARINE_CRYSTALS, "Prismarine Crystals"));
        $this->registerItem(new Item(ItemIds::RAW_COD, "Raw Cod"));
        $this->registerItem(new Item(ItemIds::RAW_SALMON, "Raw Salmon"));
        $this->registerItem(new Item(ItemIds::SCUTE, "Scute"));
        $this->registerItem(new Item(ItemIds::TROPICAL_FISH, "Tropical Fish"));
        $this->registerItem(new Item(ItemIds::TURTLE_HELMET, "Turtle Helmet"));
        $this->registerItem(new Item(ItemIds::TURTLE_SHELL, "Turtle Shell"));
        $this->registerItem(new Item(ItemIds::TURTLE_EGG, "Turtle Egg"));
        $this->registerItem(new Item(ItemIds::WET_SPONGE, "Wet Sponge"));
        $this->registerItem(new Item(ItemIds::WRITABLE_BOOK, "Writable Book"));
        $this->registerItem(new Item(ItemIds::WRITTEN_BOOK, "Written Book"));
        $this->registerItem(new Item(ItemIds::EXPERIENCE_BOTTLE, "Bottle o' Enchanting"));
        $this->registerItem(new Item(ItemIds::ENDER_CHEST, "Ender Chest"));
        $this->registerItem(new Item(ItemIds::ENCHANTED_BOOK, "Enchanted Book"));
        $this->registerItem(new Item(ItemIds::ENDER_EYE, "Eye of Ender"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_13, "Music Disc - 13"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_CAT, "Music Disc - Cat"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_BLOCKS, "Music Disc - Blocks"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_CHIRP, "Music Disc - Chirp"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_FAR, "Music Disc - Far"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_MALL, "Music Disc - Mall"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_MELLOHI, "Music Disc - Mellohi"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_STAL, "Music Disc - Stal"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_STRAD, "Music Disc - Strad"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_WAIT, "Music Disc - Wait"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_WARD, "Music Disc - Ward"));
        $this->registerItem(new Item(ItemIds::ENCHANTED_GOLDEN_APPLE, "Enchanted Golden Apple"));
        $this->registerItem(new Item(ItemIds::CARROT_ON_A_STICK, "Carrot on a Stick"));
        $this->registerItem(new Item(ItemIds::POPPED_CHORUS_FRUIT, "Popped Chorus Fruit"));
        $this->registerItem(new Item(ItemIds::CHORUS_FRUIT, "Chorus Fruit"));
        $this->registerItem(new Item(ItemIds::CHORUS_FLOWER, "Chorus Flower"));
        $this->registerItem(new Item(ItemIds::DRAGON_BREATH, "Dragon's Breath"));
        $this->registerItem(new Item(ItemIds::ELYTRA, "Elytra"));
        $this->registerItem(new Item(ItemIds::END_CRYSTAL, "End Crystal"));
        $this->registerItem(new Item(ItemIds::ELYTRA, "Elytra"));
        $this->registerItem(new Item(ItemIds::GHAST_TEAR, "Ghast Tear"));
        $this->registerItem(new Item(ItemIds::GLISTERING_MELON, "Glistering Melon"));
        $this->registerItem(new Item(ItemIds::GOLDEN_CARROT, "Golden Carrot"));
        $this->registerItem(new Item(ItemIds::INK_SAC, "Ink Sac"));
        $this->registerItem(new Item(ItemIds::LAPIS_LAZULI, "Lapis Lazuli"));
        $this->registerItem(new Item(ItemIds::MAGMA_CREAM, "Magma Cream"));
        $this->registerItem(new Item(ItemIds::MELON_SEEDS, "Melon Seeds"));
        $this->registerItem(new Item(ItemIds::NAME_TAG, "Name Tag"));
        $this->registerItem(new Item(ItemIds::NETHER_STAR, "Nether Star"));
        $this->registerItem(new Item(ItemIds::PHANTOM_MEMBRANE, "Phantom Membrane"));
        $this->registerItem(new Item(ItemIds::POPPED_CHORUS_FRUIT, "Popped Chorus Fruit"));
        $this->registerItem(new Item(ItemIds::PRISMARINE_CRYSTALS, "Prismarine Crystals"));
        $this->registerItem(new Item(ItemIds::RAW_COD, "Raw Cod"));
        $this->registerItem(new Item(ItemIds::RAW_SALMON, "Raw Salmon"));
        $this->registerItem(new Item(ItemIds::SCUTE, "Scute"));
        $this->registerItem(new Item(ItemIds::TROPICAL_FISH, "Tropical Fish"));
        $this->registerItem(new Item(ItemIds::TURTLE_HELMET, "Turtle Helmet"));
        $this->registerItem(new Item(ItemIds::TURTLE_SHELL, "Turtle Shell"));
        $this->registerItem(new Item(ItemIds::TURTLE_EGG, "Turtle Egg"));
        $this->registerItem(new Item(ItemIds::WET_SPONGE, "Wet Sponge"));
        $this->registerItem(new Item(ItemIds::WRITABLE_BOOK, "Writable Book"));
        $this->registerItem(new Item(ItemIds::WRITTEN_BOOK, "Written Book"));
        $this->registerItem(new Item(ItemIds::EXPERIENCE_BOTTLE, "Bottle o' Enchanting"));
        $this->registerItem(new Item(ItemIds::ENDER_CHEST, "Ender Chest"));
        $this->registerItem(new Item(ItemIds::ENCHANTED_BOOK, "Enchanted Book"));
        $this->registerItem(new Item(ItemIds::ENDER_EYE, "Eye of Ender"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_13, "Music Disc - 13"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_CAT, "Music Disc - Cat"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_BLOCKS, "Music Disc - Blocks"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_CHIRP, "Music Disc - Chirp"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_FAR, "Music Disc - Far"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_MALL, "Music Disc - Mall"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_MELLOHI, "Music Disc - Mellohi"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_STAL, "Music Disc - Stal"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_STRAD, "Music Disc - Strad"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_WAIT, "Music Disc - Wait"));
        $this->registerItem(new Item(ItemIds::MUSIC_DISC_WARD, "Music Disc - Ward"));
        $this->registerItem(new Item(ItemIds::ENCHANTED_GOLDEN_APPLE, "Enchanted Golden Apple"));
        $this->registerItem(new Item(ItemIds::CARROT_ON_A_STICK, "Carrot on a Stick"));
        $this->registerItem(new Item(ItemIds::POPPED_CHORUS_FRUIT, "Popped Chorus Fruit"));
        $this->registerItem(new Item(ItemIds::CHORUS_FRUIT, "Chorus Fruit"));
        $this->registerItem(new Item(ItemIds::CHORUS_FLOWER, "Chorus Flower"));
        $this->registerItem(new Item(ItemIds::DRAGON_BREATH, "Dragon's Breath"));
        $this->registerItem(new Item(ItemIds::ELYTRA, "Elytra"));
        $this->registerItem(new Item(ItemIds::END_CRYSTAL, "End Crystal"));
        $this->registerItem(new Item(ItemIds::ELYTRA, "Elytra"));
        $this->registerItem(new Item(ItemIds::GHAST_TEAR, "Ghast Tear"));
        $this->registerItem(new Item(ItemIds::GLISTERING_MELON, "Glistering Melon"));
        $this->registerItem(new Item(ItemIds::GOLDEN_CARROT, "Golden Carrot"));
        $this->registerItem(new Item(ItemIds::INK_SAC, "Ink Sac"));
        $this->registerItem(new Item(ItemIds::LAPIS_LAZULI, "Lapis Lazuli"));
        $this->registerItem(new Item(ItemIds::MAGMA_CREAM, "Magma Cream"));
        $this->registerItem(new Item(ItemIds::MELON_SEEDS, "Melon Seeds"));
        $this->registerItem(new Item(ItemIds::NAME_TAG, "Name Tag"));
        $this->registerItem(new Item(ItemIds::NETHER_STAR, "Nether Star"));
        $this->registerItem(new Item(ItemIds::PHANTOM_MEMBRANE, "Phantom Membrane"));
        $this->registerItem(new Item(ItemIds::POPPED_CHORUS_FRUIT, "Popped Chorus Fruit"));
        $this->registerItem(new Item(ItemIds::PRISMARINE_CRYSTALS, "Prismarine Crystals"));
        $this->registerItem(new Item(ItemIds::RAW_COD, "Raw Cod"));
        $this->registerItem(new Item(ItemIds::RAW_SALMON, "Raw Salmon"));
        $this->registerItem(new Item(ItemIds::SCUTE, "Scute"));
        $this->registerItem(new Item(ItemIds::TROPICAL_FISH, "Tropical Fish"));
        $this->registerItem(new Item(ItemIds::TURTLE_HELMET, "Turtle Helmet"));
        $this->registerItem(new Item(ItemIds::TURTLE_SHELL, "Turtle Shell"));
        $this->registerItem(new Item(ItemIds::TURTLE_EGG, "Turtle Egg"));
        $this->registerItem(new Item(ItemIds::WET_SPONGE, "Wet Sponge"));
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function registerBlock(Block $block): void {
        $this->blocks[$block->getId()] = $block;
    }

    public function registerItem(Item $item): void {
        $this->items[$item->getId()] = $item;
    }

    public function getBlock(int $id): ?Block {
        return $this->blocks[$id] ?? null;
    }

    public function getItem(int $id): ?Item {
        return $this->items[$id] ?? null;
    }
}
