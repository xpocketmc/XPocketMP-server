<?php

/*
 *               _ _
 *         /\   | | |
 *        /  \  | | |_ __ _ _   _
 *       / /\ \ | | __/ _` | | | |
 *      / ____ \| | || (_| | |_| |
 *     /_/    \_|_|\__\__,_|\__, |
 *                           __/ |
 *                          |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TuranicTeam
 * @link https://github.com/TuranicTeam/Altay
 *
 */

declare(strict_types=1);

namespace pocketmine\command;

use pocketmine\network\mcpe\protocol\types\CommandEnum;

class CommandEnumValues{

	private static $difficulty = [
		"normal",
		"peaceful",
		"easy",
		"hard",
		"p",
		"e",
		"n",
		"h"
	];

	private static $boolean = [
		"false",
		"true"
	];

	private static $effect = [
		"wither",
		"speed",
		"slowness",
		"haste",
		"mining_fatigue",
		"strength",
		"instant_health",
		"instant_damage",
		"jump_boost",
		"nausea",
		"regeneration",
		"resistance",
		"fire_resistance",
		"water_breathing",
		"invisibility",
		"blindness",
		"night_vision",
		"hunger",
		"weakness",
		"poison",
		"health_boost",
		"absorption",
		"saturation",
		"levitation",
		"fatal_poison"
	];

	private static $enchant = [
		"protection",
		"fire_protection",
		"feather_falling",
		"blast_protection",
		"projectile_protection",
		"thorns",
		"respiration",
		"depth_strider",
		"aqua_affinity",
		"sharpness",
		"smite",
		"bane_of_arthropods",
		"knockback",
		"fire_aspect",
		"looting",
		"efficiency",
		"silk_touch",
		"unbreaking",
		"fortune",
		"power",
		"punch",
		"flame",
		"infinity",
		"luck_of_the_sea",
		"lure",
		"frost_walker",
		"mending"
	];

	private static $gameMode = [
		"creative",
		"survival",
		"adventure",
		"c",
		"s",
		"a"
	];

	public static $item = [
		"rabbit",
		"chicken",
		"minecart",
		"slime",
		"arrow",
		"hopper_minecart",
		"tnt_minecart",
		"chest_minecart",
		"command_block_minecart",
		"tnt",
		"snowball",
		"egg",
		"painting",
		"fireball",
		"splash_potion",
		"ender_pearl",
		"boat",
		"lingering_potion",
		"armor_stand",
		"stone",
		"grass",
		"dirt",
		"cobblestone",
		"planks",
		"sapling",
		"bedrock",
		"sand",
		"gravel",
		"gold_ore",
		"iron_ore",
		"coal_ore",
		"log",
		"leaves",
		"sponge",
		"glass",
		"lapis_ore",
		"lapis_block",
		"dispenser",
		"sandstone",
		"noteblock",
		"bed",
		"golden_rail",
		"detector_rail",
		"sticky_piston",
		"web",
		"tallgrass",
		"deadbush",
		"piston",
		"wool",
		"yellow_flower",
		"red_flower",
		"brown_mushroom",
		"red_mushroom",
		"gold_block",
		"iron_block",
		"stone_slab",
		"brick_block",
		"bookshelf",
		"mossy_cobblestone",
		"obsidian",
		"torch",
		"mob_spawner",
		"oak_stairs",
		"chest",
		"diamond_ore",
		"diamond_block",
		"crafting_table",
		"wheat",
		"furnace",
		"wooden_door",
		"ladder",
		"rail",
		"stone_stairs",
		"lever",
		"stone_pressure_plate",
		"iron_door",
		"wooden_pressure_plate",
		"redstone_ore",
		"redstone_torch",
		"stone_button",
		"snow_layer",
		"ice",
		"snow",
		"cactus",
		"clay",
		"reeds",
		"jukebox",
		"fence",
		"pumpkin",
		"netherrack",
		"soul_sand",
		"glowstone",
		"lit_pumpkin",
		"cake",
		"trapdoor",
		"monster_egg",
		"stonebrick",
		"brown_mushroom_block",
		"red_mushroom_block",
		"iron_bars",
		"glass_pane",
		"melon_block",
		"vine",
		"fence_gate",
		"brick_stairs",
		"stone_brick_stairs",
		"mycelium",
		"waterlily",
		"nether_brick",
		"nether_brick_fence",
		"nether_brick_stairs",
		"nether_wart",
		"enchanting_table",
		"brewing_stand",
		"cauldron",
		"end_portal_frame",
		"end_stone",
		"dragon_egg",
		"redstone_lamp",
		"dropper",
		"activator_rail",
		"sandstone_stairs",
		"emerald_ore",
		"ender_chest",
		"tripwire_hook",
		"emerald_block",
		"spruce_stairs",
		"birch_stairs",
		"jungle_stairs",
		"command_block",
		"beacon",
		"cobblestone_wall",
		"flower_pot",
		"wooden_button",
		"skull",
		"anvil",
		"trapped_chest",
		"light_weighted_pressure_plate",
		"heavy_weighted_pressure_plate",
		"daylight_detector",
		"redstone_block",
		"quartz_ore",
		"hopper",
		"quartz_block",
		"quartz_stairs",
		"wooden_slab",
		"stained_hardened_clay",
		"stained_glass_pane",
		"leaves2",
		"log2",
		"acacia_stairs",
		"dark_oak_stairs",
		"iron_trapdoor",
		"prismarine",
		"sealantern",
		"hay_block",
		"carpet",
		"hardened_clay",
		"coal_block",
		"packed_ice",
		"double_plant",
		"red_sandstone",
		"red_sandstone_stairs",
		"stone_slab2",
		"spruce_fence_gate",
		"birch_fence_gate",
		"jungle_fence_gate",
		"dark_oak_fence_gate",
		"acacia_fence_gate",
		"repeating_command_block",
		"chain_command_block",
		"spruce_door",
		"birch_door",
		"jungle_door",
		"acacia_door",
		"dark_oak_door",
		"frame",
		"chorus_flower",
		"purpur_block",
		"purpur_stairs",
		"undyed_shulker_box",
		"end_bricks",
		"end_rod",
		"magma",
		"nether_wart_block",
		"red_nether_brick",
		"bone_block",
		"shulker_box",
		"purple_glazed_terracotta",
		"white_glazed_terracotta",
		"orange_glazed_terracotta",
		"magenta_glazed_terracotta",
		"light_blue_glazed_terracotta",
		"yellow_glazed_terracotta",
		"lime_glazed_terracotta",
		"pink_glazed_terracotta",
		"gray_glazed_terracotta",
		"silver_glazed_terracotta",
		"cyan_glazed_terracotta",
		"blue_glazed_terracotta",
		"brown_glazed_terracotta",
		"green_glazed_terracotta",
		"red_glazed_terracotta",
		"black_glazed_terracotta",
		"concrete",
		"concretepowder",
		"chorus_plant",
		"stained_glass",
		"podzol",
		"beetroot",
		"stonecutter",
		"observer",
		"structure_block",
		"wheat_seeds",
		"pumpkin_seeds",
		"melon_seeds",
		"beetroot_seeds",
		"potato",
		"poisonous_potato",
		"carrot",
		"golden_carrot",
		"apple",
		"golden_apple",
		"appleenchanted",
		"melon",
		"speckled_melon",
		"dye",
		"porkchop",
		"beef",
		"muttonraw",
		"fish",
		"salmon",
		"clownfish",
		"pufferfish",
		"sugar",
		"rotten_flesh",
		"bone",
		"spider_eye",
		"spawn_egg",
		"chorus_fruit",
		"chorus_fruit_popped",
		"leather_helmet",
		"chainmail_helmet",
		"iron_helmet",
		"golden_helmet",
		"diamond_helmet",
		"leather_chestplate",
		"chainmail_chestplate",
		"iron_chestplate",
		"golden_chestplate",
		"diamond_chestplate",
		"leather_leggings",
		"chainmail_leggings",
		"iron_leggings",
		"golden_leggings",
		"diamond_leggings",
		"leather_boots",
		"chainmail_boots",
		"iron_boots",
		"golden_boots",
		"diamond_boots",
		"wooden_sword",
		"stone_sword",
		"iron_sword",
		"golden_sword",
		"diamond_sword",
		"wooden_axe",
		"stone_axe",
		"iron_axe",
		"golden_axe",
		"diamond_axe",
		"wooden_pickaxe",
		"stone_pickaxe",
		"iron_pickaxe",
		"golden_pickaxe",
		"diamond_pickaxe",
		"wooden_shovel",
		"stone_shovel",
		"iron_shovel",
		"golden_shovel",
		"diamond_shovel",
		"wooden_hoe",
		"stone_hoe",
		"iron_hoe",
		"golden_hoe",
		"diamond_hoe",
		"bow",
		"cooked_chicken",
		"cooked_porkchop",
		"cooked_beef",
		"muttoncooked",
		"cooked_rabbit",
		"cooked_fish",
		"cooked_salmon",
		"bread",
		"mushroom_stew",
		"beetroot_soup",
		"rabbit_stew",
		"baked_potato",
		"cookie",
		"pumpkin_pie",
		"bucket",
		"fishing_rod",
		"carrotonastick",
		"shears",
		"flint_and_steel",
		"lead",
		"clock",
		"compass",
		"emptymap",
		"saddle",
		"horsearmorleather",
		"horsearmoriron",
		"horsearmorgold",
		"horsearmordiamond",
		"elytra",
		"totem",
		"glass_bottle",
		"experience_bottle",
		"potion",
		"stick",
		"record_13",
		"record_cat",
		"record_blocks",
		"record_chirp",
		"record_far",
		"record_mall",
		"record_mellohi",
		"record_stal",
		"record_strad",
		"record_ward",
		"record_11",
		"record_wait",
		"glowstone_dust",
		"sign",
		"bowl",
		"coal",
		"diamond",
		"iron_nugget",
		"iron_ingot",
		"gold_nugget",
		"gold_ingot",
		"emerald",
		"quartz",
		"clay_ball",
		"brick",
		"netherbrick",
		"prismarine_shard",
		"prismarine_crystals",
		"string",
		"feather",
		"flint",
		"gunpowder",
		"leather",
		"rabbit_hide",
		"rabbit_foot",
		"blaze_rod",
		"blaze_powder",
		"magma_cream",
		"fermented_spider_eye",
		"dragon_breath",
		"shulker_shell",
		"ghast_tear",
		"slime_ball",
		"ender_eye",
		"netherstar",
		"end_crystal",
		"paper",
		"book",
		"writable_book",
		"enchanted_book",
		"redstone",
		"repeater",
		"comparator",
		"nametag",
		"banner",
		"fireworks",
		"fireworkscharge",
		"map"
	];

	private static $block = [
		"slime",
		"tnt",
		"air",
		"stone",
		"grass",
		"dirt",
		"cobblestone",
		"planks",
		"sapling",
		"bedrock",
		"flowing_water",
		"water",
		"flowing_lava",
		"lava",
		"sand",
		"gravel",
		"gold_ore",
		"iron_ore",
		"coal_ore",
		"log",
		"leaves",
		"sponge",
		"glass",
		"lapis_ore",
		"lapis_block",
		"dispenser",
		"sandstone",
		"noteblock",
		"bed",
		"golden_rail",
		"detector_rail",
		"sticky_piston",
		"web",
		"tallgrass",
		"deadbush",
		"piston",
		"pistonarmcollision",
		"wool",
		"yellow_flower",
		"red_flower",
		"brown_mushroom",
		"red_mushroom",
		"gold_block",
		"iron_block",
		"double_stone_slab",
		"stone_slab",
		"brick_block",
		"bookshelf",
		"mossy_cobblestone",
		"obsidian",
		"torch",
		"fire",
		"mob_spawner",
		"oak_stairs",
		"chest",
		"redstone_wire",
		"diamond_ore",
		"diamond_block",
		"crafting_table",
		"wheat",
		"farmland",
		"furnace",
		"lit_furnace",
		"standing_sign",
		"wooden_door",
		"ladder",
		"rail",
		"stone_stairs",
		"wall_sign",
		"lever",
		"stone_pressure_plate",
		"iron_door",
		"wooden_pressure_plate",
		"redstone_ore",
		"lit_redstone_ore",
		"unlit_redstone_torch",
		"redstone_torch",
		"stone_button",
		"snow_layer",
		"ice",
		"snow",
		"cactus",
		"clay",
		"reeds",
		"jukebox",
		"fence",
		"pumpkin",
		"netherrack",
		"soul_sand",
		"glowstone",
		"portal",
		"lit_pumpkin",
		"cake",
		"unpowered_repeater",
		"powered_repeater",
		"trapdoor",
		"monster_egg",
		"stonebrick",
		"brown_mushroom_block",
		"red_mushroom_block",
		"iron_bars",
		"glass_pane",
		"melon_block",
		"pumpkin_stem",
		"melon_stem",
		"vine",
		"fence_gate",
		"brick_stairs",
		"stone_brick_stairs",
		"mycelium",
		"waterlily",
		"nether_brick",
		"nether_brick_fence",
		"nether_brick_stairs",
		"nether_wart",
		"enchanting_table",
		"brewing_stand",
		"cauldron",
		"end_portal_frame",
		"end_stone",
		"dragon_egg",
		"redstone_lamp",
		"lit_redstone_lamp",
		"dropper",
		"activator_rail",
		"cocoa",
		"sandstone_stairs",
		"emerald_ore",
		"ender_chest",
		"tripwire_hook",
		"tripwire",
		"emerald_block",
		"spruce_stairs",
		"birch_stairs",
		"jungle_stairs",
		"command_block",
		"beacon",
		"cobblestone_wall",
		"flower_pot",
		"carrots",
		"potatoes",
		"wooden_button",
		"skull",
		"anvil",
		"trapped_chest",
		"light_weighted_pressure_plate",
		"heavy_weighted_pressure_plate",
		"unpowered_comparator",
		"powered_comparator",
		"daylight_detector",
		"redstone_block",
		"quartz_ore",
		"hopper",
		"quartz_block",
		"quartz_stairs",
		"double_wooden_slab",
		"wooden_slab",
		"stained_hardened_clay",
		"stained_glass_pane",
		"leaves2",
		"log2",
		"acacia_stairs",
		"dark_oak_stairs",
		"iron_trapdoor",
		"prismarine",
		"sealantern",
		"hay_block",
		"carpet",
		"hardened_clay",
		"coal_block",
		"packed_ice",
		"double_plant",
		"standing_banner",
		"wall_banner",
		"daylight_detector_inverted",
		"red_sandstone",
		"red_sandstone_stairs",
		"double_stone_slab2",
		"stone_slab2",
		"spruce_fence_gate",
		"birch_fence_gate",
		"jungle_fence_gate",
		"dark_oak_fence_gate",
		"acacia_fence_gate",
		"repeating_command_block",
		"chain_command_block",
		"spruce_door",
		"birch_door",
		"jungle_door",
		"acacia_door",
		"dark_oak_door",
		"grass_path",
		"frame",
		"chorus_flower",
		"purpur_block",
		"purpur_stairs",
		"undyed_shulker_box",
		"end_bricks",
		"end_rod",
		"magma",
		"nether_wart_block",
		"red_nether_brick",
		"bone_block",
		"shulker_box",
		"purple_glazed_terracotta",
		"white_glazed_terracotta",
		"orange_glazed_terracotta",
		"magenta_glazed_terracotta",
		"light_blue_glazed_terracotta",
		"yellow_glazed_terracotta",
		"lime_glazed_terracotta",
		"pink_glazed_terracotta",
		"gray_glazed_terracotta",
		"silver_glazed_terracotta",
		"cyan_glazed_terracotta",
		"blue_glazed_terracotta",
		"brown_glazed_terracotta",
		"green_glazed_terracotta",
		"red_glazed_terracotta",
		"black_glazed_terracotta",
		"concrete",
		"concretepowder",
		"chorus_plant",
		"stained_glass",
		"podzol",
		"beetroot",
		"stonecutter",
		"observer",
		"structure_block"
	];

	private static $timeSpec = [
		"day",
		"sunrise",
		"noon",
		"sunset",
		"night",
		"midnight"
	];

	private static $timeQuery = [
		"daytime",
		"gametime",
		"day"
	];

	private static $titleSet = [
		"title",
		"subtitle",
		"actionbar"
	];

	private static $setBlockMode = [
		"replace",
		"destroy",
		"keep"
	];

	// ALTAY (Vanilla hasn't got)
	private static $altayGameMode = ["view", "v"];

	public static function getDifficulty() : CommandEnum{
		return new CommandEnum("Difficulty", self::$difficulty);
	}

	public static function getBoolean() : CommandEnum{
		return new CommandEnum("Boolean", self::$boolean);
	}

	public static function getEffect() : CommandEnum{
		return new CommandEnum("Effect", self::$effect);
	}

	public static function getEnchant() : CommandEnum{
		return new CommandEnum("Enchant", self::$enchant);
	}

	public static function getGameMode() : CommandEnum{
		return new CommandEnum("GameMode", array_merge(self::$gameMode, self::$altayGameMode));
	}

	public static function getItem() : CommandEnum{
		return new CommandEnum("Item", array_merge(self::$item, self::$block));
	}

	public static function getBlock() : CommandEnum{
		return new CommandEnum("Block", self::$block);
	}

	public static function getTimeSpec() : CommandEnum{
		return new CommandEnum("TimeSpec", self::$timeSpec);
	}

	public static function getTimeQuery() : CommandEnum{
		return new CommandEnum("TimeQuery", self::$timeQuery);
	}

	public static function getTitleSet(): CommandEnum{
		return new CommandEnum("TitleSet", self::$titleSet);
	}

	public static function getSetBlockMode() : CommandEnum{
		return new CommandEnum("SetBlockMode", self::$setBlockMode);
	}

}