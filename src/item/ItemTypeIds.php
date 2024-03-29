<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\item;

/**
 * Every item in {@link VanillaItems} has a corresponding constant in this class. These constants can be used to
 * identify and compare item types efficiently using {@link Item::getTypeId()}.
 *
 * WARNING: These are NOT a replacement for Minecraft legacy IDs. Do **NOT** hardcode their values, or store them in
 * configs or databases. They will change without warning.
 */
final class ItemTypeIds{

	private function __construct(){
		//NOOP
	}

	public const CHAINMAIL_BOOTS = 20000;
	public const DIAMOND_BOOTS = self::CHAINMAIL_BOOTS + 1;
	public const GOLDEN_BOOTS = self::DIAMOND_BOOTS + 1;
	public const IRON_BOOTS = self::GOLDEN_BOOTS + 1;
	public const LEATHER_BOOTS = self::IRON_BOOTS + 1;
	public const NETHERITE_BOOTS = self::LEATHER_BOOTS + 1;
	public const CHAINMAIL_CHESTPLATE = self::NETHERITE_BOOTS + 1;
	public const DIAMOND_CHESTPLATE = self::CHAINMAIL_CHESTPLATE + 1;
	public const GOLDEN_CHESTPLATE = self::DIAMOND_CHESTPLATE + 1;
	public const IRON_CHESTPLATE = self::GOLDEN_CHESTPLATE + 1;
	public const LEATHER_TUNIC = self::IRON_CHESTPLATE + 1;
	public const NETHERITE_CHESTPLATE = self::LEATHER_TUNIC + 1;
	public const CHAINMAIL_HELMET = self::NETHERITE_CHESTPLATE + 1;
	public const DIAMOND_HELMET = self::CHAINMAIL_HELMET + 1;
	public const GOLDEN_HELMET = self::DIAMOND_HELMET + 1;
	public const IRON_HELMET = self::GOLDEN_HELMET + 1;
	public const LEATHER_CAP = self::IRON_HELMET + 1;
	public const NETHERITE_HELMET = self::LEATHER_CAP + 1;
	public const TURTLE_HELMET = self::NETHERITE_HELMET + 1;
	public const CHAINMAIL_LEGGINGS = self::TURTLE_HELMET + 1;
	public const DIAMOND_LEGGINGS = self::CHAINMAIL_LEGGINGS + 1;
	public const GOLDEN_LEGGINGS = self::DIAMOND_LEGGINGS + 1;
	public const IRON_LEGGINGS = self::GOLDEN_LEGGINGS + 1;
	public const LEATHER_PANTS = self::IRON_LEGGINGS + 1;
	public const NETHERITE_LEGGINGS = self::LEATHER_PANTS + 1;
	public const ZOMBIE_SPAWN_EGG = self::NETHERITE_LEGGINGS + 1;
	public const SQUID_SPAWN_EGG = self::ZOMBIE_SPAWN_EGG + 1;
	public const VILLAGER_SPAWN_EGG = self::SQUID_SPAWN_EGG + 1;
	public const DIAMOND_AXE = self::VILLAGER_SPAWN_EGG + 1;
	public const GOLDEN_AXE = self::DIAMOND_AXE + 1;
	public const IRON_AXE = self::GOLDEN_AXE + 1;
	public const NETHERITE_AXE = self::IRON_AXE + 1;
	public const STONE_AXE = self::NETHERITE_AXE + 1;
	public const WOODEN_AXE = self::STONE_AXE + 1;
	public const DIAMOND_HOE = self::WOODEN_AXE + 1;
	public const GOLDEN_HOE = self::DIAMOND_HOE + 1;
	public const IRON_HOE = self::GOLDEN_HOE + 1;
	public const NETHERITE_HOE = self::IRON_HOE + 1;
	public const STONE_HOE = self::NETHERITE_HOE + 1;
	public const WOODEN_HOE = self::STONE_HOE + 1;
	public const DIAMOND_PICKAXE = self::WOODEN_HOE + 1;
	public const GOLDEN_PICKAXE = self::DIAMOND_PICKAXE + 1;
	public const IRON_PICKAXE = self::GOLDEN_PICKAXE + 1;
	public const NETHERITE_PICKAXE = self::IRON_PICKAXE + 1;
	public const STONE_PICKAXE = self::NETHERITE_PICKAXE + 1;
	public const WOODEN_PICKAXE = self::STONE_PICKAXE + 1;
	public const DIAMOND_SHOVEL = self::WOODEN_PICKAXE + 1;
	public const GOLDEN_SHOVEL = self::DIAMOND_SHOVEL + 1;
	public const IRON_SHOVEL = self::GOLDEN_SHOVEL + 1;
	public const NETHERITE_SHOVEL = self::IRON_SHOVEL + 1;
	public const STONE_SHOVEL = self::NETHERITE_SHOVEL + 1;
	public const WOODEN_SHOVEL = self::STONE_SHOVEL + 1;
	public const DIAMOND_SWORD = self::WOODEN_SHOVEL + 1;
	public const GOLDEN_SWORD = self::DIAMOND_SWORD + 1;
	public const IRON_SWORD = self::GOLDEN_SWORD + 1;
	public const NETHERITE_SWORD = self::IRON_SWORD + 1;
	public const STONE_SWORD = self::NETHERITE_SWORD + 1;
	public const WOODEN_SWORD = self::STONE_SWORD + 1;
	public const NETHERITE_UPGRADE_SMITHING_TEMPLATE = self::WOODEN_SWORD + 1;
	public const COAST_ARMOR_TRIM_SMITHING_TEMPLATE = self::NETHERITE_UPGRADE_SMITHING_TEMPLATE + 1;
	public const DUNE_ARMOR_TRIM_SMITHING_TEMPLATE = self::COAST_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const EYE_ARMOR_TRIM_SMITHING_TEMPLATE = self::DUNE_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const HOST_ARMOR_TRIM_SMITHING_TEMPLATE = self::EYE_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const RAISER_ARMOR_TRIM_SMITHING_TEMPLATE = self::HOST_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const RIB_ARMOR_TRIM_SMITHING_TEMPLATE = self::RAISER_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const SENTRY_ARMOR_TRIM_SMITHING_TEMPLATE = self::RIB_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const SHAPER_ARMOR_TRIM_SMITHING_TEMPLATE = self::SENTRY_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const SILENCE_ARMOR_TRIM_SMITHING_TEMPLATE = self::SHAPER_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const SNOUT_ARMOR_TRIM_SMITHING_TEMPLATE = self::SILENCE_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const SPIRE_ARMOR_TRIM_SMITHING_TEMPLATE = self::SNOUT_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const TIDE_ARMOR_TRIM_SMITHING_TEMPLATE = self::SPIRE_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const VEX_ARMOR_TRIM_SMITHING_TEMPLATE = self::TIDE_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const WARD_ARMOR_TRIM_SMITHING_TEMPLATE = self::VEX_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const WAYFINDER_ARMOR_TRIM_SMITHING_TEMPLATE = self::WARD_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const WILD_ARMOR_TRIM_SMITHING_TEMPLATE = self::WAYFINDER_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const ACACIA_SIGN = self::WILD_ARMOR_TRIM_SMITHING_TEMPLATE + 1;
	public const AMETHYST_SHARD = self::ACACIA_SIGN + 1;
	public const APPLE = self::AMETHYST_SHARD + 1;
	public const ARROW = self::APPLE + 1;
	public const BAKED_POTATO = self::ARROW + 1;
	public const BAMBOO = self::BAKED_POTATO + 1;
	public const BANNER = self::BAMBOO + 1;
	public const BEETROOT = self::BANNER + 1;
	public const BEETROOT_SEEDS = self::BEETROOT + 1;
	public const BEETROOT_SOUP = self::BEETROOT_SEEDS + 1;
	public const BIRCH_SIGN = self::BEETROOT_SOUP + 1;
	public const BLAZE_POWDER = self::BIRCH_SIGN + 1;
	public const BLAZE_ROD = self::BLAZE_POWDER + 1;
	public const BLEACH = self::BLAZE_ROD + 1;
	public const BONE = self::BLEACH + 1;
	public const BONE_MEAL = self::BONE + 1;
	public const BOOK = self::BONE_MEAL + 1;
	public const BOW = self::BOOK + 1;
	public const BOWL = self::BOW + 1;
	public const BREAD = self::BOWL + 1;
	public const BRICK = self::BREAD + 1;
	public const BUCKET = self::BRICK + 1;
	public const CARROT = self::BUCKET + 1;
	public const CHARCOAL = self::CARROT + 1;
	public const CHERRY_SIGN = self::CHARCOAL + 1;
	public const CHEMICAL_ALUMINIUM_OXIDE = self::CHERRY_SIGN + 1;
	public const CHEMICAL_AMMONIA = self::CHEMICAL_ALUMINIUM_OXIDE + 1;
	public const CHEMICAL_BARIUM_SULPHATE = self::CHEMICAL_AMMONIA + 1;
	public const CHEMICAL_BENZENE = self::CHEMICAL_BARIUM_SULPHATE + 1;
	public const CHEMICAL_BORON_TRIOXIDE = self::CHEMICAL_BENZENE + 1;
	public const CHEMICAL_CALCIUM_BROMIDE = self::CHEMICAL_BORON_TRIOXIDE + 1;
	public const CHEMICAL_CALCIUM_CHLORIDE = self::CHEMICAL_CALCIUM_BROMIDE + 1;
	public const CHEMICAL_CERIUM_CHLORIDE = self::CHEMICAL_CALCIUM_CHLORIDE + 1;
	public const CHEMICAL_CHARCOAL = self::CHEMICAL_CERIUM_CHLORIDE + 1;
	public const CHEMICAL_CRUDE_OIL = self::CHEMICAL_CHARCOAL + 1;
	public const CHEMICAL_GLUE = self::CHEMICAL_CRUDE_OIL + 1;
	public const CHEMICAL_HYDROGEN_PEROXIDE = self::CHEMICAL_GLUE + 1;
	public const CHEMICAL_HYPOCHLORITE = self::CHEMICAL_HYDROGEN_PEROXIDE + 1;
	public const CHEMICAL_INK = self::CHEMICAL_HYPOCHLORITE + 1;
	public const CHEMICAL_IRON_SULPHIDE = self::CHEMICAL_INK + 1;
	public const CHEMICAL_LATEX = self::CHEMICAL_IRON_SULPHIDE + 1;
	public const CHEMICAL_LITHIUM_HYDRIDE = self::CHEMICAL_LATEX + 1;
	public const CHEMICAL_LUMINOL = self::CHEMICAL_LITHIUM_HYDRIDE + 1;
	public const CHEMICAL_MAGNESIUM_NITRATE = self::CHEMICAL_LUMINOL + 1;
	public const CHEMICAL_MAGNESIUM_OXIDE = self::CHEMICAL_MAGNESIUM_NITRATE + 1;
	public const CHEMICAL_MAGNESIUM_SALTS = self::CHEMICAL_MAGNESIUM_OXIDE + 1;
	public const CHEMICAL_MERCURIC_CHLORIDE = self::CHEMICAL_MAGNESIUM_SALTS + 1;
	public const CHEMICAL_POLYETHYLENE = self::CHEMICAL_MERCURIC_CHLORIDE + 1;
	public const CHEMICAL_POTASSIUM_CHLORIDE = self::CHEMICAL_POLYETHYLENE + 1;
	public const CHEMICAL_POTASSIUM_IODIDE = self::CHEMICAL_POTASSIUM_CHLORIDE + 1;
	public const CHEMICAL_RUBBISH = self::CHEMICAL_POTASSIUM_IODIDE + 1;
	public const CHEMICAL_SALT = self::CHEMICAL_RUBBISH + 1;
	public const CHEMICAL_SOAP = self::CHEMICAL_SALT + 1;
	public const CHEMICAL_SODIUM_ACETATE = self::CHEMICAL_SOAP + 1;
	public const CHEMICAL_SODIUM_FLUORIDE = self::CHEMICAL_SODIUM_ACETATE + 1;
	public const CHEMICAL_SODIUM_HYDRIDE = self::CHEMICAL_SODIUM_FLUORIDE + 1;
	public const CHEMICAL_SODIUM_HYDROXIDE = self::CHEMICAL_SODIUM_HYDRIDE + 1;
	public const CHEMICAL_SODIUM_HYPOCHLORITE = self::CHEMICAL_SODIUM_HYDROXIDE + 1;
	public const CHEMICAL_SODIUM_OXIDE = self::CHEMICAL_SODIUM_HYPOCHLORITE + 1;
	public const CHEMICAL_SUGAR = self::CHEMICAL_SODIUM_OXIDE + 1;
	public const CHEMICAL_SULPHATE = self::CHEMICAL_SUGAR + 1;
	public const CHEMICAL_TUNGSTEN_CHLORIDE = self::CHEMICAL_SULPHATE + 1;
	public const CHEMICAL_WATER = self::CHEMICAL_TUNGSTEN_CHLORIDE + 1;
	public const CHORUS_FRUIT = self::CHEMICAL_WATER + 1;
	public const CLAY = self::CHORUS_FRUIT + 1;
	public const CLOCK = self::CLAY + 1;
	public const CLOWNFISH = self::CLOCK + 1;
	public const COAL = self::CLOWNFISH + 1;
	public const COCOA_BEANS = self::COAL + 1;
	public const COMPASS = self::COCOA_BEANS + 1;
	public const COOKED_CHICKEN = self::COMPASS + 1;
	public const COOKED_FISH = self::COOKED_CHICKEN + 1;
	public const COOKED_MUTTON = self::COOKED_FISH + 1;
	public const COOKED_PORKCHOP = self::COOKED_MUTTON + 1;
	public const COOKED_RABBIT = self::COOKED_PORKCHOP + 1;
	public const COOKED_SALMON = self::COOKED_RABBIT + 1;
	public const COOKIE = self::COOKED_SALMON + 1;
	public const COPPER_INGOT = self::COOKIE + 1;
	public const CORAL_FAN = self::COPPER_INGOT + 1;
	public const CRIMSON_SIGN = self::CORAL_FAN + 1;
	public const DARK_OAK_SIGN = self::CRIMSON_SIGN + 1;
	public const DIAMOND = self::DARK_OAK_SIGN + 1;
	public const DISC_FRAGMENT_5 = self::DIAMOND + 1;
	public const DRAGON_BREATH = self::DISC_FRAGMENT_5 + 1;
	public const DRIED_KELP = self::DRAGON_BREATH + 1;
	public const DYE = self::DRIED_KELP + 1;
	public const ECHO_SHARD = self::DYE + 1;
	public const EGG = self::ECHO_SHARD + 1;
	public const EMERALD = self::EGG + 1;
	public const ENCHANTED_BOOK = self::EMERALD + 1;
	public const ENCHANTED_GOLDEN_APPLE = self::ENCHANTED_BOOK + 1;
	public const ENDER_PEARL = self::ENCHANTED_GOLDEN_APPLE + 1;
	public const EXPERIENCE_BOTTLE = self::ENDER_PEARL + 1;
	public const FEATHER = self::EXPERIENCE_BOTTLE + 1;
	public const FERMENTED_SPIDER_EYE = self::FEATHER + 1;
	public const FIRE_CHARGE = self::FERMENTED_SPIDER_EYE + 1;
	public const FISHING_ROD = self::FIRE_CHARGE + 1;
	public const FLINT = self::FISHING_ROD + 1;
	public const FLINT_AND_STEEL = self::FLINT + 1;
	public const GHAST_TEAR = self::FLINT_AND_STEEL + 1;
	public const GLASS_BOTTLE = self::GHAST_TEAR + 1;
	public const GLISTERING_MELON = self::GLASS_BOTTLE + 1;
	public const GLOW_BERRIES = self::GLISTERING_MELON + 1;
	public const GLOW_INK_SAC = self::GLOW_BERRIES + 1;
	public const GLOWSTONE_DUST = self::GLOW_INK_SAC + 1;
	public const GOLD_INGOT = self::GLOWSTONE_DUST + 1;
	public const GOLD_NUGGET = self::GOLD_INGOT + 1;
	public const GOLDEN_APPLE = self::GOLD_NUGGET + 1;
	public const GOLDEN_CARROT = self::GOLDEN_APPLE + 1;
	public const GUNPOWDER = self::GOLDEN_CARROT + 1;
	public const HEART_OF_THE_SEA = self::GUNPOWDER + 1;
	public const HONEY_BOTTLE = self::HEART_OF_THE_SEA + 1;
	public const HONEYCOMB = self::HONEY_BOTTLE + 1;
	public const INK_SAC = self::HONEYCOMB + 1;
	public const IRON_INGOT = self::INK_SAC + 1;
	public const IRON_NUGGET = self::IRON_INGOT + 1;
	public const JUNGLE_SIGN = self::IRON_NUGGET + 1;
	public const LAPIS_LAZULI = self::JUNGLE_SIGN + 1;
	public const LAVA_BUCKET = self::LAPIS_LAZULI + 1;
	public const LEATHER = self::LAVA_BUCKET + 1;
	public const MAGMA_CREAM = self::LEATHER + 1;
	public const MANGROVE_SIGN = self::MAGMA_CREAM + 1;
	public const MEDICINE = self::MANGROVE_SIGN + 1;
	public const MELON = self::MEDICINE + 1;
	public const MELON_SEEDS = self::MELON + 1;
	public const MILK_BUCKET = self::MELON_SEEDS + 1;
	public const MINECART = self::MILK_BUCKET + 1;
	public const MUSHROOM_STEW = self::MINECART + 1;
	public const NAME_TAG = self::MUSHROOM_STEW + 1;
	public const NAUTILUS_SHELL = self::NAME_TAG + 1;
	public const NETHER_BRICK = self::NAUTILUS_SHELL + 1;
	public const NETHER_QUARTZ = self::NETHER_BRICK + 1;
	public const NETHER_STAR = self::NETHER_QUARTZ + 1;
	public const NETHERITE_INGOT = self::NETHER_STAR + 1;
	public const NETHERITE_SCRAP = self::NETHERITE_INGOT + 1;
	public const OAK_SIGN = self::NETHERITE_SCRAP + 1;
	public const PAINTING = self::OAK_SIGN + 1;
	public const PAPER = self::PAINTING + 1;
	public const PHANTOM_MEMBRANE = self::PAPER + 1;
	public const PITCHER_POD = self::PHANTOM_MEMBRANE + 1;
	public const POISONOUS_POTATO = self::PITCHER_POD + 1;
	public const POPPED_CHORUS_FRUIT = self::POISONOUS_POTATO + 1;
	public const POTATO = self::POPPED_CHORUS_FRUIT + 1;
	public const POTION = self::POTATO + 1;
	public const PRISMARINE_CRYSTALS = self::POTION + 1;
	public const PRISMARINE_SHARD = self::PRISMARINE_CRYSTALS + 1;
	public const PUFFERFISH = self::PRISMARINE_SHARD + 1;
	public const PUMPKIN_PIE = self::PUFFERFISH + 1;
	public const PUMPKIN_SEEDS = self::PUMPKIN_PIE + 1;
	public const RABBIT_FOOT = self::PUMPKIN_SEEDS + 1;
	public const RABBIT_HIDE = self::RABBIT_FOOT + 1;
	public const RABBIT_STEW = self::RABBIT_HIDE + 1;
	public const RAW_BEEF = self::RABBIT_STEW + 1;
	public const RAW_CHICKEN = self::RAW_BEEF + 1;
	public const RAW_COPPER = self::RAW_CHICKEN + 1;
	public const RAW_FISH = self::RAW_COPPER + 1;
	public const RAW_GOLD = self::RAW_FISH + 1;
	public const RAW_IRON = self::RAW_GOLD + 1;
	public const RAW_MUTTON = self::RAW_IRON + 1;
	public const RAW_PORKCHOP = self::RAW_MUTTON + 1;
	public const RAW_RABBIT = self::RAW_PORKCHOP + 1;
	public const RAW_SALMON = self::RAW_RABBIT + 1;
	public const RECORD_11 = self::RAW_SALMON + 1;
	public const RECORD_13 = self::RECORD_11 + 1;
	public const RECORD_5 = self::RECORD_13 + 1;
	public const RECORD_BLOCKS = self::RECORD_5 + 1;
	public const RECORD_CAT = self::RECORD_BLOCKS + 1;
	public const RECORD_CHIRP = self::RECORD_CAT + 1;
	public const RECORD_FAR = self::RECORD_CHIRP + 1;
	public const RECORD_MALL = self::RECORD_FAR + 1;
	public const RECORD_MELLOHI = self::RECORD_MALL + 1;
	public const RECORD_OTHERSIDE = self::RECORD_MELLOHI + 1;
	public const RECORD_PIGSTEP = self::RECORD_OTHERSIDE + 1;
	public const RECORD_STAL = self::RECORD_PIGSTEP + 1;
	public const RECORD_STRAD = self::RECORD_STAL + 1;
	public const RECORD_WAIT = self::RECORD_STRAD + 1;
	public const RECORD_WARD = self::RECORD_WAIT + 1;
	public const REDSTONE_DUST = self::RECORD_WARD + 1;
	public const ROTTEN_FLESH = self::REDSTONE_DUST + 1;
	public const SCUTE = self::ROTTEN_FLESH + 1;
	public const SHEARS = self::SCUTE + 1;
	public const SHULKER_SHELL = self::SHEARS + 1;
	public const SLIMEBALL = self::SHULKER_SHELL + 1;
	public const SNOWBALL = self::SLIMEBALL + 1;
	public const SPIDER_EYE = self::SNOWBALL + 1;
	public const SPLASH_POTION = self::SPIDER_EYE + 1;
	public const SPRUCE_SIGN = self::SPLASH_POTION + 1;
	public const SPYGLASS = self::SPRUCE_SIGN + 1;
	public const STEAK = self::SPYGLASS + 1;
	public const STICK = self::STEAK + 1;
	public const STRING = self::STICK + 1;
	public const SUGAR = self::STRING + 1;
	public const SUSPICIOUS_STEW = self::SUGAR + 1;
	public const SWEET_BERRIES = self::SUSPICIOUS_STEW + 1;
	public const TORCHFLOWER_SEEDS = self::SWEET_BERRIES + 1;
	public const TOTEM = self::TORCHFLOWER_SEEDS + 1;
	public const WARPED_SIGN = self::TOTEM + 1;
	public const WATER_BUCKET = self::WARPED_SIGN + 1;
	public const WHEAT = self::WATER_BUCKET + 1;
	public const WHEAT_SEEDS = self::WHEAT + 1;
	public const WRITABLE_BOOK = self::WHEAT_SEEDS + 1;
	public const WRITTEN_BOOK = self::WRITABLE_BOOK + 1;
	public const OAK_BOAT = self::WRITTEN_BOOK + 1;
	public const SPRUCE_BOAT = self::OAK_BOAT + 1;
	public const BIRCH_BOAT = self::SPRUCE_BOAT + 1;
	public const JUNGLE_BOAT = self::BIRCH_BOAT + 1;
	public const ACACIA_BOAT = self::JUNGLE_BOAT + 1;
	public const DARK_OAK_BOAT = self::ACACIA_BOAT + 1;
	public const MANGROVE_BOAT = self::DARK_OAK_BOAT + 1;
	public const POWDER_SNOW_BUCKET = self::MANGROVE_BOAT + 1;
	public const LINGERING_POTION = self::POWDER_SNOW_BUCKET + 1;

	public const FIRST_UNUSED_ITEM_ID = self::LINGERING_POTION + 1;

	private static int $nextDynamicId = self::FIRST_UNUSED_ITEM_ID;

	/**
	 * Returns a new runtime item type ID, e.g. for use by a custom item.
	 */
	public static function newId() : int{
		return self::$nextDynamicId++;
	}

	public static function fromBlockTypeId(int $blockTypeId) : int{
		if($blockTypeId < 0){
			throw new \InvalidArgumentException("Block type IDs cannot be negative");
		}
		//negative item type IDs are treated as block IDs
		return -$blockTypeId;
	}

	public static function toBlockTypeId(int $itemTypeId) : ?int{
		if($itemTypeId > 0){ //not a blockitem
			return null;
		}
		return -$itemTypeId;
	}
}
