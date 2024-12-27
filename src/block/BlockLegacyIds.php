<?php

/*
 *
 *  __  ______            _        _   __  __ ____
 *  \ \/ /  _ \ ___   ___| | _____| |_|  \/  |  _ \
 *   \  /| |_) / _ \ / __| |/ / _ \ __| |\/| | |_) |
 *   /  \|  __/ (_) | (__|   <  __/ |_| |  | |  __/
 *  /_/\_\_|   \___/ \___|_|\_\___|\__|_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License as published by
 * the Free Software Foundation
 * The files in XPocketMP are mostly from PocketMine-MP.
 * Developed by ClousClouds, PMMP Team
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\block;

class BlockLegacyIds {

  public function __construct(){
	//NOOP
  }

  public const AIR = 0;
  public const ACACIA_BUTTON = 1;
  public const ACACIA_DOOR = 2;
  public const ACACIA_FENCE = 3;
  public const ACACIA_FENCE_GATE = 4;
  public const ACACIA_LEAVES = 5;
  public const ACACIA_LOG = 6;
  public const ACACIA_PLANKS = 7;
  public const ACACIA_PRESSURE_PLATE = 8;
	public const ACACIA_SAPLING = 9;
	public const ACACIA_SIGN = 10;
	public const ACACIA_SLAB = 11;
	public const ACACIA_STAIRS = 12;
	public const ACACIA_TRAPDOOR = 13;
	public const ACACIA_WALL_SIGN = 14;
	public const ACACIA_WOOD = 15;
	public const ACTIVATOR_RAIL = 16;
	public const ALL_SIDED_MUSHROOM_STEM = 17;
	public const ALLIUM = 18;
	public const ANDESITE = 19;
	public const ANDESITE_SLAB = 20;
	public const ANDESITE_STAIRS = 21;
	public const ANDESITE_WALL = 22;
	public const ANVIL = 23;
	public const AZURE_BLUET = 24;
	public const BAMBOO = 25;
	public const BAMBOO_SAPLING = 26;
	public const BANNER = 27;
	public const BARREL = 28;
	public const BARRIER = 29;
	public const BEACON = 30;
	public const BED = 31;
	public const BEDROCK = 32;
	public const BEETROOTS = 33;
	public const BELL = 34;
	public const BIRCH_BUTTON = 35;
	public const BIRCH_DOOR = 36;
	public const BIRCH_FENCE = 37;
	public const BIRCH_FENCE_GATE = 38;
	public const BIRCH_LEAVES = 39;
	public const BIRCH_LOG = 40;
	public const BIRCH_PLANKS = 41;
	public const BIRCH_PRESSURE_PLATE = 42;
	public const BIRCH_SAPLING = 43;
	public const BIRCH_SIGN = 44;
	public const BIRCH_SLAB = 45;
	public const BIRCH_STAIRS = 46;
	public const BIRCH_TRAPDOOR = 47;
	public const BIRCH_WALL_SIGN = 48;
	public const BIRCH_WOOD = 49;

	public const BLAST_FURNACE = 51;

	public const BLUE_ICE = 53;
	public const BLUE_ORCHID = 54;
	public const BLUE_TORCH = 55;
	public const BONE_BLOCK = 56;
	public const BOOKSHELF = 57;
	public const BREWING_STAND = 58;
	public const BRICK_SLAB = 59;
	public const BRICK_STAIRS = 60;
	public const BRICK_WALL = 61;
	public const BRICKS = 62;

	public const BROWN_MUSHROOM = 64;
	public const BROWN_MUSHROOM_BLOCK = 65;
	public const CACTUS = 66;
	public const CAKE = 67;
	public const CARPET = 68;
	public const CARROTS = 69;
	public const CARVED_PUMPKIN = 70;
	public const CHEMICAL_HEAT = 71;
	public const CHEST = 72;
	public const CHISELED_QUARTZ = 73;
	public const CHISELED_RED_SANDSTONE = 74;
	public const CHISELED_SANDSTONE = 75;
	public const CHISELED_STONE_BRICKS = 76;
	public const CLAY = 77;
	public const COAL = 78;
	public const COAL_ORE = 79;
	public const COBBLESTONE = 80;
	public const COBBLESTONE_SLAB = 81;
	public const COBBLESTONE_STAIRS = 82;
	public const COBBLESTONE_WALL = 83;
	public const COBWEB = 84;
	public const COCOA_POD = 85;
	public const COMPOUND_CREATOR = 86;
	public const CONCRETE = 87;
	public const CONCRETE_POWDER = 88;
	public const CORAL = 89;
	public const CORAL_BLOCK = 90;
	public const CORAL_FAN = 91;
	public const CORNFLOWER = 92;
	public const CRACKED_STONE_BRICKS = 93;
	public const CRAFTING_TABLE = 94;
	public const CUT_RED_SANDSTONE = 95;
	public const CUT_RED_SANDSTONE_SLAB = 96;
	public const CUT_SANDSTONE = 97;
	public const CUT_SANDSTONE_SLAB = 98;

	public const DANDELION = 100;
	public const DARK_OAK_BUTTON = 101;
	public const DARK_OAK_DOOR = 102;
	public const DARK_OAK_FENCE = 103;
	public const DARK_OAK_FENCE_GATE = 104;
	public const DARK_OAK_LEAVES = 105;
	public const DARK_OAK_LOG = 106;
	public const DARK_OAK_PLANKS = 107;
	public const DARK_OAK_PRESSURE_PLATE = 108;
	public const DARK_OAK_SAPLING = 109;
	public const DARK_OAK_SIGN = 110;
	public const DARK_OAK_SLAB = 111;
	public const DARK_OAK_STAIRS = 112;
	public const DARK_OAK_TRAPDOOR = 113;
	public const DARK_OAK_WALL_SIGN = 114;
	public const DARK_OAK_WOOD = 115;
	public const DARK_PRISMARINE = 116;
	public const DARK_PRISMARINE_SLAB = 117;
	public const DARK_PRISMARINE_STAIRS = 118;
	public const DAYLIGHT_SENSOR = 119;
	public const DEAD_BUSH = 120;
	public const DETECTOR_RAIL = 121;
	public const DIAMOND = 122;
	public const DIAMOND_ORE = 123;
	public const DIORITE = 124;
	public const DIORITE_SLAB = 125;
	public const DIORITE_STAIRS = 126;
	public const DIORITE_WALL = 127;
	public const DIRT = 128;
	public const DOUBLE_TALLGRASS = 129;
	public const DRAGON_EGG = 130;
	public const DRIED_KELP = 131;
	public const DYED_SHULKER_BOX = 132;
	public const ELEMENT_ACTINIUM = 133;
	public const ELEMENT_ALUMINUM = 134;
	public const ELEMENT_AMERICIUM = 135;
	public const ELEMENT_ANTIMONY = 136;
	public const ELEMENT_ARGON = 137;
	public const ELEMENT_ARSENIC = 138;
	public const ELEMENT_ASTATINE = 139;
	public const ELEMENT_BARIUM = 140;
	public const ELEMENT_BERKELIUM = 141;
	public const ELEMENT_BERYLLIUM = 142;
	public const ELEMENT_BISMUTH = 143;
	public const ELEMENT_BOHRIUM = 144;
	public const ELEMENT_BORON = 145;
	public const ELEMENT_BROMINE = 146;
	public const ELEMENT_CADMIUM = 147;
	public const ELEMENT_CALCIUM = 148;
	public const ELEMENT_CALIFORNIUM = 149;
	public const ELEMENT_CARBON = 150;
	public const ELEMENT_CERIUM = 151;
	public const ELEMENT_CESIUM = 152;
	public const ELEMENT_CHLORINE = 153;
	public const ELEMENT_CHROMIUM = 154;
	public const ELEMENT_COBALT = 155;
	public const ELEMENT_CONSTRUCTOR = 156;
	public const ELEMENT_COPERNICIUM = 157;
	public const ELEMENT_COPPER = 158;
	public const ELEMENT_CURIUM = 159;
	public const ELEMENT_DARMSTADTIUM = 160;
	public const ELEMENT_DUBNIUM = 161;
	public const ELEMENT_DYSPROSIUM = 162;
	public const ELEMENT_EINSTEINIUM = 163;
	public const ELEMENT_ERBIUM = 164;
	public const ELEMENT_EUROPIUM = 165;
	public const ELEMENT_FERMIUM = 166;
	public const ELEMENT_FLEROVIUM = 167;
	public const ELEMENT_FLUORINE = 168;
	public const ELEMENT_FRANCIUM = 169;
	public const ELEMENT_GADOLINIUM = 170;
	public const ELEMENT_GALLIUM = 171;
	public const ELEMENT_GERMANIUM = 172;
	public const ELEMENT_GOLD = 173;
	public const ELEMENT_HAFNIUM = 174;
	public const ELEMENT_HASSIUM = 175;
	public const ELEMENT_HELIUM = 176;
	public const ELEMENT_HOLMIUM = 177;
	public const ELEMENT_HYDROGEN = 178;
	public const ELEMENT_INDIUM = 179;
	public const ELEMENT_IODINE = 180;
	public const ELEMENT_IRIDIUM = 181;
	public const ELEMENT_IRON = 182;
	public const ELEMENT_KRYPTON = 183;
	public const ELEMENT_LANTHANUM = 184;
	public const ELEMENT_LAWRENCIUM = 185;
	public const ELEMENT_LEAD = 186;
	public const ELEMENT_LITHIUM = 187;
	public const ELEMENT_LIVERMORIUM = 188;
	public const ELEMENT_LUTETIUM = 189;
	public const ELEMENT_MAGNESIUM = 190;
	public const ELEMENT_MANGANESE = 191;
	public const ELEMENT_MEITNERIUM = 192;
	public const ELEMENT_MENDELEVIUM = 193;
	public const ELEMENT_MERCURY = 194;
	public const ELEMENT_MOLYBDENUM = 195;
	public const ELEMENT_MOSCOVIUM = 196;
	public const ELEMENT_NEODYMIUM = 197;
	public const ELEMENT_NEON = 198;
	public const ELEMENT_NEPTUNIUM = 199;
	public const ELEMENT_NICKEL = 200;
	public const ELEMENT_NIHONIUM = 201;
	public const ELEMENT_NIOBIUM = 202;
	public const ELEMENT_NITROGEN = 203;
	public const ELEMENT_NOBELIUM = 204;
	public const ELEMENT_OGANESSON = 205;
	public const ELEMENT_OSMIUM = 206;
	public const ELEMENT_OXYGEN = 207;
	public const ELEMENT_PALLADIUM = 208;
	public const ELEMENT_PHOSPHORUS = 209;
	public const ELEMENT_PLATINUM = 2;
	public const ELEMENT_PLUTONIUM = 211;
	public const ELEMENT_POLONIUM = 212;
	public const ELEMENT_POTASSIUM = 213;
	public const ELEMENT_PRASEODYMIUM = 214;
	public const ELEMENT_PROMETHIUM = 215;
	public const ELEMENT_PROTACTINIUM = 216;
	public const ELEMENT_RADIUM = 217;
	public const ELEMENT_RADON = 218;
	public const ELEMENT_RHENIUM = 219;
	public const ELEMENT_RHODIUM = 220;
	public const ELEMENT_ROENTGENIUM = 221;
	public const ELEMENT_RUBIDIUM = 222;
	public const ELEMENT_RUTHENIUM = 223;
	public const ELEMENT_RUTHERFORDIUM = 224;
	public const ELEMENT_SAMARIUM = 225;
	public const ELEMENT_SCANDIUM = 226;
	public const ELEMENT_SEABORGIUM = 227;
	public const ELEMENT_SELENIUM = 228;
	public const ELEMENT_SILICON = 229;
	public const ELEMENT_SILVER = 230;
	public const ELEMENT_SODIUM = 231;
	public const ELEMENT_STRONTIUM = 232;
	public const ELEMENT_SULFUR = 233;
	public const ELEMENT_TANTALUM = 234;
	public const ELEMENT_TECHNETIUM = 235;
	public const ELEMENT_TELLURIUM = 236;
	public const ELEMENT_TENNESSINE = 237;
	public const ELEMENT_TERBIUM = 238;
	public const ELEMENT_THALLIUM = 239;
	public const ELEMENT_THORIUM = 240;
	public const ELEMENT_THULIUM = 241;
	public const ELEMENT_TIN = 242;
	public const ELEMENT_TITANIUM = 243;
	public const ELEMENT_TUNGSTEN = 244;
	public const ELEMENT_URANIUM = 245;
	public const ELEMENT_VANADIUM = 246;
	public const ELEMENT_XENON = 247;
	public const ELEMENT_YTTERBIUM = 248;
	public const ELEMENT_YTTRIUM = 249;
	public const ELEMENT_ZERO = 250;
	public const ELEMENT_ZINC = 251;
	public const ELEMENT_ZIRCONIUM = 252;
	public const EMERALD = 253;
	public const EMERALD_ORE = 254;
	public const ENCHANTING_TABLE = 255;
}
