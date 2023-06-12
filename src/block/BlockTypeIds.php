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

namespace pocketmine\block;

/**
 * Every block in {@link VanillaBlocks} has a corresponding constant in this class. These constants can be used to
 * identify and compare block types efficiently using {@link Block::getTypeId()}.
 *
 * Type ID is also used internally as part of block state ID, which is used to store blocks and their simple properties
 * in a memory-efficient way in chunks at runtime.
 *
 * WARNING: These are NOT a replacement for Minecraft legacy IDs. Do **NOT** hardcode their values, or store them in
 * configs or databases. They will change without warning.
 */
final class BlockTypeIds{

	private function __construct(){
		//NOOP
	}

	public const AIR = 10000;

	public const ACACIA_BUTTON = 10001;
	public const ACACIA_DOOR = 10002;
	public const ACACIA_FENCE = 10003;
	public const ACACIA_FENCE_GATE = 10004;
	public const ACACIA_LEAVES = 10005;
	public const ACACIA_LOG = 10006;
	public const ACACIA_PLANKS = 10007;
	public const ACACIA_PRESSURE_PLATE = 10008;
	public const ACACIA_SAPLING = 10009;
	public const ACACIA_SIGN = 10010;
	public const ACACIA_SLAB = 10011;
	public const ACACIA_STAIRS = 10012;
	public const ACACIA_TRAPDOOR = 10013;
	public const ACACIA_WALL_SIGN = 10014;
	public const ACACIA_WOOD = 10015;
	public const ACTIVATOR_RAIL = 10016;
	public const ALL_SIDED_MUSHROOM_STEM = 10017;
	public const ALLIUM = 10018;
	public const ANDESITE = 10019;
	public const ANDESITE_SLAB = 10020;
	public const ANDESITE_STAIRS = 10021;
	public const ANDESITE_WALL = 10022;
	public const ANVIL = 10023;
	public const AZURE_BLUET = 10024;
	public const BAMBOO = 10025;
	public const BAMBOO_SAPLING = 10026;
	public const BANNER = 10027;
	public const BARREL = 10028;
	public const BARRIER = 10029;
	public const BEACON = 10030;
	public const BED = 10031;
	public const BEDROCK = 10032;
	public const BEETROOTS = 10033;
	public const BELL = 10034;
	public const BIRCH_BUTTON = 10035;
	public const BIRCH_DOOR = 10036;
	public const BIRCH_FENCE = 10037;
	public const BIRCH_FENCE_GATE = 10038;
	public const BIRCH_LEAVES = 10039;
	public const BIRCH_LOG = 10040;
	public const BIRCH_PLANKS = 10041;
	public const BIRCH_PRESSURE_PLATE = 10042;
	public const BIRCH_SAPLING = 10043;
	public const BIRCH_SIGN = 10044;
	public const BIRCH_SLAB = 10045;
	public const BIRCH_STAIRS = 10046;
	public const BIRCH_TRAPDOOR = 10047;
	public const BIRCH_WALL_SIGN = 10048;
	public const BIRCH_WOOD = 10049;

	public const BLAST_FURNACE = 10051;

	public const BLUE_ICE = 10053;
	public const BLUE_ORCHID = 10054;
	public const BLUE_TORCH = 10055;
	public const BONE_BLOCK = 10056;
	public const BOOKSHELF = 10057;
	public const BREWING_STAND = 10058;
	public const BRICK_SLAB = 10059;
	public const BRICK_STAIRS = 10060;
	public const BRICK_WALL = 10061;
	public const BRICKS = 10062;

	public const BROWN_MUSHROOM = 10064;
	public const BROWN_MUSHROOM_BLOCK = 10065;
	public const CACTUS = 10066;
	public const CAKE = 10067;
	public const CARPET = 10068;
	public const CARROTS = 10069;
	public const CARVED_PUMPKIN = 10070;
	public const CHEMICAL_HEAT = 10071;
	public const CHEST = 10072;
	public const CHISELED_QUARTZ = 10073;
	public const CHISELED_RED_SANDSTONE = 10074;
	public const CHISELED_SANDSTONE = 10075;
	public const CHISELED_STONE_BRICKS = 10076;
	public const CLAY = 10077;
	public const COAL = 10078;
	public const COAL_ORE = 10079;
	public const COBBLESTONE = 10080;
	public const COBBLESTONE_SLAB = 10081;
	public const COBBLESTONE_STAIRS = 10082;
	public const COBBLESTONE_WALL = 10083;
	public const COBWEB = 10084;
	public const COCOA_POD = 10085;
	public const COMPOUND_CREATOR = 10086;
	public const CONCRETE = 10087;
	public const CONCRETE_POWDER = 10088;
	public const CORAL = 10089;
	public const CORAL_BLOCK = 10090;
	public const CORAL_FAN = 10091;
	public const CORNFLOWER = 10092;
	public const CRACKED_STONE_BRICKS = 10093;
	public const CRAFTING_TABLE = 10094;
	public const CUT_RED_SANDSTONE = 10095;
	public const CUT_RED_SANDSTONE_SLAB = 10096;
	public const CUT_SANDSTONE = 10097;
	public const CUT_SANDSTONE_SLAB = 10098;

	public const DANDELION = 10100;
	public const DARK_OAK_BUTTON = 10101;
	public const DARK_OAK_DOOR = 10102;
	public const DARK_OAK_FENCE = 10103;
	public const DARK_OAK_FENCE_GATE = 10104;
	public const DARK_OAK_LEAVES = 10105;
	public const DARK_OAK_LOG = 10106;
	public const DARK_OAK_PLANKS = 10107;
	public const DARK_OAK_PRESSURE_PLATE = 10108;
	public const DARK_OAK_SAPLING = 10109;
	public const DARK_OAK_SIGN = 10110;
	public const DARK_OAK_SLAB = 10111;
	public const DARK_OAK_STAIRS = 10112;
	public const DARK_OAK_TRAPDOOR = 10113;
	public const DARK_OAK_WALL_SIGN = 10114;
	public const DARK_OAK_WOOD = 10115;
	public const DARK_PRISMARINE = 10116;
	public const DARK_PRISMARINE_SLAB = 10117;
	public const DARK_PRISMARINE_STAIRS = 10118;
	public const DAYLIGHT_SENSOR = 10119;
	public const DEAD_BUSH = 10120;
	public const DETECTOR_RAIL = 10121;
	public const DIAMOND = 10122;
	public const DIAMOND_ORE = 10123;
	public const DIORITE = 10124;
	public const DIORITE_SLAB = 10125;
	public const DIORITE_STAIRS = 10126;
	public const DIORITE_WALL = 10127;
	public const DIRT = 10128;
	public const DOUBLE_TALLGRASS = 10129;
	public const DRAGON_EGG = 10130;
	public const DRIED_KELP = 10131;
	public const DYED_SHULKER_BOX = 10132;
	public const ELEMENT_ACTINIUM = 10133;
	public const ELEMENT_ALUMINUM = 10134;
	public const ELEMENT_AMERICIUM = 10135;
	public const ELEMENT_ANTIMONY = 10136;
	public const ELEMENT_ARGON = 10137;
	public const ELEMENT_ARSENIC = 10138;
	public const ELEMENT_ASTATINE = 10139;
	public const ELEMENT_BARIUM = 10140;
	public const ELEMENT_BERKELIUM = 10141;
	public const ELEMENT_BERYLLIUM = 10142;
	public const ELEMENT_BISMUTH = 10143;
	public const ELEMENT_BOHRIUM = 10144;
	public const ELEMENT_BORON = 10145;
	public const ELEMENT_BROMINE = 10146;
	public const ELEMENT_CADMIUM = 10147;
	public const ELEMENT_CALCIUM = 10148;
	public const ELEMENT_CALIFORNIUM = 10149;
	public const ELEMENT_CARBON = 10150;
	public const ELEMENT_CERIUM = 10151;
	public const ELEMENT_CESIUM = 10152;
	public const ELEMENT_CHLORINE = 10153;
	public const ELEMENT_CHROMIUM = 10154;
	public const ELEMENT_COBALT = 10155;
	public const ELEMENT_CONSTRUCTOR = 10156;
	public const ELEMENT_COPERNICIUM = 10157;
	public const ELEMENT_COPPER = 10158;
	public const ELEMENT_CURIUM = 10159;
	public const ELEMENT_DARMSTADTIUM = 10160;
	public const ELEMENT_DUBNIUM = 10161;
	public const ELEMENT_DYSPROSIUM = 10162;
	public const ELEMENT_EINSTEINIUM = 10163;
	public const ELEMENT_ERBIUM = 10164;
	public const ELEMENT_EUROPIUM = 10165;
	public const ELEMENT_FERMIUM = 10166;
	public const ELEMENT_FLEROVIUM = 10167;
	public const ELEMENT_FLUORINE = 10168;
	public const ELEMENT_FRANCIUM = 10169;
	public const ELEMENT_GADOLINIUM = 10170;
	public const ELEMENT_GALLIUM = 10171;
	public const ELEMENT_GERMANIUM = 10172;
	public const ELEMENT_GOLD = 10173;
	public const ELEMENT_HAFNIUM = 10174;
	public const ELEMENT_HASSIUM = 10175;
	public const ELEMENT_HELIUM = 10176;
	public const ELEMENT_HOLMIUM = 10177;
	public const ELEMENT_HYDROGEN = 10178;
	public const ELEMENT_INDIUM = 10179;
	public const ELEMENT_IODINE = 10180;
	public const ELEMENT_IRIDIUM = 10181;
	public const ELEMENT_IRON = 10182;
	public const ELEMENT_KRYPTON = 10183;
	public const ELEMENT_LANTHANUM = 10184;
	public const ELEMENT_LAWRENCIUM = 10185;
	public const ELEMENT_LEAD = 10186;
	public const ELEMENT_LITHIUM = 10187;
	public const ELEMENT_LIVERMORIUM = 10188;
	public const ELEMENT_LUTETIUM = 10189;
	public const ELEMENT_MAGNESIUM = 10190;
	public const ELEMENT_MANGANESE = 10191;
	public const ELEMENT_MEITNERIUM = 10192;
	public const ELEMENT_MENDELEVIUM = 10193;
	public const ELEMENT_MERCURY = 10194;
	public const ELEMENT_MOLYBDENUM = 10195;
	public const ELEMENT_MOSCOVIUM = 10196;
	public const ELEMENT_NEODYMIUM = 10197;
	public const ELEMENT_NEON = 10198;
	public const ELEMENT_NEPTUNIUM = 10199;
	public const ELEMENT_NICKEL = 10200;
	public const ELEMENT_NIHONIUM = 10201;
	public const ELEMENT_NIOBIUM = 10202;
	public const ELEMENT_NITROGEN = 10203;
	public const ELEMENT_NOBELIUM = 10204;
	public const ELEMENT_OGANESSON = 10205;
	public const ELEMENT_OSMIUM = 10206;
	public const ELEMENT_OXYGEN = 10207;
	public const ELEMENT_PALLADIUM = 10208;
	public const ELEMENT_PHOSPHORUS = 10209;
	public const ELEMENT_PLATINUM = 10210;
	public const ELEMENT_PLUTONIUM = 10211;
	public const ELEMENT_POLONIUM = 10212;
	public const ELEMENT_POTASSIUM = 10213;
	public const ELEMENT_PRASEODYMIUM = 10214;
	public const ELEMENT_PROMETHIUM = 10215;
	public const ELEMENT_PROTACTINIUM = 10216;
	public const ELEMENT_RADIUM = 10217;
	public const ELEMENT_RADON = 10218;
	public const ELEMENT_RHENIUM = 10219;
	public const ELEMENT_RHODIUM = 10220;
	public const ELEMENT_ROENTGENIUM = 10221;
	public const ELEMENT_RUBIDIUM = 10222;
	public const ELEMENT_RUTHENIUM = 10223;
	public const ELEMENT_RUTHERFORDIUM = 10224;
	public const ELEMENT_SAMARIUM = 10225;
	public const ELEMENT_SCANDIUM = 10226;
	public const ELEMENT_SEABORGIUM = 10227;
	public const ELEMENT_SELENIUM = 10228;
	public const ELEMENT_SILICON = 10229;
	public const ELEMENT_SILVER = 10230;
	public const ELEMENT_SODIUM = 10231;
	public const ELEMENT_STRONTIUM = 10232;
	public const ELEMENT_SULFUR = 10233;
	public const ELEMENT_TANTALUM = 10234;
	public const ELEMENT_TECHNETIUM = 10235;
	public const ELEMENT_TELLURIUM = 10236;
	public const ELEMENT_TENNESSINE = 10237;
	public const ELEMENT_TERBIUM = 10238;
	public const ELEMENT_THALLIUM = 10239;
	public const ELEMENT_THORIUM = 10240;
	public const ELEMENT_THULIUM = 10241;
	public const ELEMENT_TIN = 10242;
	public const ELEMENT_TITANIUM = 10243;
	public const ELEMENT_TUNGSTEN = 10244;
	public const ELEMENT_URANIUM = 10245;
	public const ELEMENT_VANADIUM = 10246;
	public const ELEMENT_XENON = 10247;
	public const ELEMENT_YTTERBIUM = 10248;
	public const ELEMENT_YTTRIUM = 10249;
	public const ELEMENT_ZERO = 10250;
	public const ELEMENT_ZINC = 10251;
	public const ELEMENT_ZIRCONIUM = 10252;
	public const EMERALD = 10253;
	public const EMERALD_ORE = 10254;
	public const ENCHANTING_TABLE = 10255;
	public const END_PORTAL_FRAME = 10256;
	public const END_ROD = 10257;
	public const END_STONE = 10258;
	public const END_STONE_BRICK_SLAB = 10259;
	public const END_STONE_BRICK_STAIRS = 10260;
	public const END_STONE_BRICK_WALL = 10261;
	public const END_STONE_BRICKS = 10262;
	public const ENDER_CHEST = 10263;
	public const FAKE_WOODEN_SLAB = 10264;
	public const FARMLAND = 10265;
	public const FERN = 10266;
	public const FIRE = 10267;
	public const FLETCHING_TABLE = 10268;
	public const FLOWER_POT = 10269;
	public const FROSTED_ICE = 10270;
	public const FURNACE = 10271;
	public const GLASS = 10272;
	public const GLASS_PANE = 10273;
	public const GLOWING_OBSIDIAN = 10274;
	public const GLOWSTONE = 10275;
	public const GOLD = 10276;
	public const GOLD_ORE = 10277;
	public const GRANITE = 10278;
	public const GRANITE_SLAB = 10279;
	public const GRANITE_STAIRS = 10280;
	public const GRANITE_WALL = 10281;
	public const GRASS = 10282;
	public const GRASS_PATH = 10283;
	public const GRAVEL = 10284;

	public const GREEN_TORCH = 10287;
	public const HARDENED_CLAY = 10288;
	public const HARDENED_GLASS = 10289;
	public const HARDENED_GLASS_PANE = 10290;
	public const HAY_BALE = 10291;
	public const HOPPER = 10292;
	public const ICE = 10293;
	public const INFESTED_CHISELED_STONE_BRICK = 10294;
	public const INFESTED_COBBLESTONE = 10295;
	public const INFESTED_CRACKED_STONE_BRICK = 10296;
	public const INFESTED_MOSSY_STONE_BRICK = 10297;
	public const INFESTED_STONE = 10298;
	public const INFESTED_STONE_BRICK = 10299;
	public const INFO_UPDATE = 10300;
	public const INFO_UPDATE2 = 10301;
	public const INVISIBLE_BEDROCK = 10302;
	public const IRON = 10303;
	public const IRON_BARS = 10304;
	public const IRON_DOOR = 10305;
	public const IRON_ORE = 10306;
	public const IRON_TRAPDOOR = 10307;
	public const ITEM_FRAME = 10308;
	public const JUKEBOX = 10309;
	public const JUNGLE_BUTTON = 10310;
	public const JUNGLE_DOOR = 10311;
	public const JUNGLE_FENCE = 10312;
	public const JUNGLE_FENCE_GATE = 10313;
	public const JUNGLE_LEAVES = 10314;
	public const JUNGLE_LOG = 10315;
	public const JUNGLE_PLANKS = 10316;
	public const JUNGLE_PRESSURE_PLATE = 10317;
	public const JUNGLE_SAPLING = 10318;
	public const JUNGLE_SIGN = 10319;
	public const JUNGLE_SLAB = 10320;
	public const JUNGLE_STAIRS = 10321;
	public const JUNGLE_TRAPDOOR = 10322;
	public const JUNGLE_WALL_SIGN = 10323;
	public const JUNGLE_WOOD = 10324;
	public const LAB_TABLE = 10325;
	public const LADDER = 10326;
	public const LANTERN = 10327;
	public const LAPIS_LAZULI = 10328;
	public const LAPIS_LAZULI_ORE = 10329;
	public const LARGE_FERN = 10330;
	public const LAVA = 10331;
	public const LECTERN = 10332;
	public const LEGACY_STONECUTTER = 10333;
	public const LEVER = 10334;

	public const LILAC = 10337;
	public const LILY_OF_THE_VALLEY = 10338;
	public const LILY_PAD = 10339;

	public const LIT_PUMPKIN = 10341;
	public const LOOM = 10342;

	public const MAGMA = 10344;
	public const MATERIAL_REDUCER = 10345;
	public const MELON = 10346;
	public const MELON_STEM = 10347;
	public const MOB_HEAD = 10348;
	public const MONSTER_SPAWNER = 10349;
	public const MOSSY_COBBLESTONE = 10350;
	public const MOSSY_COBBLESTONE_SLAB = 10351;
	public const MOSSY_COBBLESTONE_STAIRS = 10352;
	public const MOSSY_COBBLESTONE_WALL = 10353;
	public const MOSSY_STONE_BRICK_SLAB = 10354;
	public const MOSSY_STONE_BRICK_STAIRS = 10355;
	public const MOSSY_STONE_BRICK_WALL = 10356;
	public const MOSSY_STONE_BRICKS = 10357;
	public const MUSHROOM_STEM = 10358;
	public const MYCELIUM = 10359;
	public const NETHER_BRICK_FENCE = 10360;
	public const NETHER_BRICK_SLAB = 10361;
	public const NETHER_BRICK_STAIRS = 10362;
	public const NETHER_BRICK_WALL = 10363;
	public const NETHER_BRICKS = 10364;
	public const NETHER_PORTAL = 10365;
	public const NETHER_QUARTZ_ORE = 10366;
	public const NETHER_REACTOR_CORE = 10367;
	public const NETHER_WART = 10368;
	public const NETHER_WART_BLOCK = 10369;
	public const NETHERRACK = 10370;
	public const NOTE_BLOCK = 10371;
	public const OAK_BUTTON = 10372;
	public const OAK_DOOR = 10373;
	public const OAK_FENCE = 10374;
	public const OAK_FENCE_GATE = 10375;
	public const OAK_LEAVES = 10376;
	public const OAK_LOG = 10377;
	public const OAK_PLANKS = 10378;
	public const OAK_PRESSURE_PLATE = 10379;
	public const OAK_SAPLING = 10380;
	public const OAK_SIGN = 10381;
	public const OAK_SLAB = 10382;
	public const OAK_STAIRS = 10383;
	public const OAK_TRAPDOOR = 10384;
	public const OAK_WALL_SIGN = 10385;
	public const OAK_WOOD = 10386;
	public const OBSIDIAN = 10387;

	public const ORANGE_TULIP = 10389;
	public const OXEYE_DAISY = 10390;
	public const PACKED_ICE = 10391;
	public const PEONY = 10392;

	public const PINK_TULIP = 10394;
	public const PODZOL = 10395;
	public const POLISHED_ANDESITE = 10396;
	public const POLISHED_ANDESITE_SLAB = 10397;
	public const POLISHED_ANDESITE_STAIRS = 10398;
	public const POLISHED_DIORITE = 10399;
	public const POLISHED_DIORITE_SLAB = 10400;
	public const POLISHED_DIORITE_STAIRS = 10401;
	public const POLISHED_GRANITE = 10402;
	public const POLISHED_GRANITE_SLAB = 10403;
	public const POLISHED_GRANITE_STAIRS = 10404;
	public const POPPY = 10405;
	public const POTATOES = 10406;
	public const POWERED_RAIL = 10407;
	public const PRISMARINE = 10408;
	public const PRISMARINE_BRICKS = 10409;
	public const PRISMARINE_BRICKS_SLAB = 10410;
	public const PRISMARINE_BRICKS_STAIRS = 10411;
	public const PRISMARINE_SLAB = 10412;
	public const PRISMARINE_STAIRS = 10413;
	public const PRISMARINE_WALL = 10414;
	public const PUMPKIN = 10415;
	public const PUMPKIN_STEM = 10416;

	public const PURPLE_TORCH = 10418;
	public const PURPUR = 10419;
	public const PURPUR_PILLAR = 10420;
	public const PURPUR_SLAB = 10421;
	public const PURPUR_STAIRS = 10422;
	public const QUARTZ = 10423;
	public const QUARTZ_PILLAR = 10424;
	public const QUARTZ_SLAB = 10425;
	public const QUARTZ_STAIRS = 10426;
	public const RAIL = 10427;

	public const RED_MUSHROOM = 10429;
	public const RED_MUSHROOM_BLOCK = 10430;
	public const RED_NETHER_BRICK_SLAB = 10431;
	public const RED_NETHER_BRICK_STAIRS = 10432;
	public const RED_NETHER_BRICK_WALL = 10433;
	public const RED_NETHER_BRICKS = 10434;
	public const RED_SAND = 10435;
	public const RED_SANDSTONE = 10436;
	public const RED_SANDSTONE_SLAB = 10437;
	public const RED_SANDSTONE_STAIRS = 10438;
	public const RED_SANDSTONE_WALL = 10439;
	public const RED_TORCH = 10440;
	public const RED_TULIP = 10441;
	public const REDSTONE = 10442;
	public const REDSTONE_COMPARATOR = 10443;
	public const REDSTONE_LAMP = 10444;
	public const REDSTONE_ORE = 10445;
	public const REDSTONE_REPEATER = 10446;
	public const REDSTONE_TORCH = 10447;
	public const REDSTONE_WIRE = 10448;
	public const RESERVED6 = 10449;
	public const ROSE_BUSH = 10450;
	public const SAND = 10451;
	public const SANDSTONE = 10452;
	public const SANDSTONE_SLAB = 10453;
	public const SANDSTONE_STAIRS = 10454;
	public const SANDSTONE_WALL = 10455;
	public const SEA_LANTERN = 10456;
	public const SEA_PICKLE = 10457;
	public const SHULKER_BOX = 10458;
	public const SLIME = 10459;
	public const SMOKER = 10460;
	public const SMOOTH_QUARTZ = 10461;
	public const SMOOTH_QUARTZ_SLAB = 10462;
	public const SMOOTH_QUARTZ_STAIRS = 10463;
	public const SMOOTH_RED_SANDSTONE = 10464;
	public const SMOOTH_RED_SANDSTONE_SLAB = 10465;
	public const SMOOTH_RED_SANDSTONE_STAIRS = 10466;
	public const SMOOTH_SANDSTONE = 10467;
	public const SMOOTH_SANDSTONE_SLAB = 10468;
	public const SMOOTH_SANDSTONE_STAIRS = 10469;
	public const SMOOTH_STONE = 10470;
	public const SMOOTH_STONE_SLAB = 10471;
	public const SNOW = 10472;
	public const SNOW_LAYER = 10473;
	public const SOUL_SAND = 10474;
	public const SPONGE = 10475;
	public const SPRUCE_BUTTON = 10476;
	public const SPRUCE_DOOR = 10477;
	public const SPRUCE_FENCE = 10478;
	public const SPRUCE_FENCE_GATE = 10479;
	public const SPRUCE_LEAVES = 10480;
	public const SPRUCE_LOG = 10481;
	public const SPRUCE_PLANKS = 10482;
	public const SPRUCE_PRESSURE_PLATE = 10483;
	public const SPRUCE_SAPLING = 10484;
	public const SPRUCE_SIGN = 10485;
	public const SPRUCE_SLAB = 10486;
	public const SPRUCE_STAIRS = 10487;
	public const SPRUCE_TRAPDOOR = 10488;
	public const SPRUCE_WALL_SIGN = 10489;
	public const SPRUCE_WOOD = 10490;
	public const STAINED_CLAY = 10491;
	public const STAINED_GLASS = 10492;
	public const STAINED_GLASS_PANE = 10493;
	public const STAINED_HARDENED_GLASS = 10494;
	public const STAINED_HARDENED_GLASS_PANE = 10495;
	public const STONE = 10496;
	public const STONE_BRICK_SLAB = 10497;
	public const STONE_BRICK_STAIRS = 10498;
	public const STONE_BRICK_WALL = 10499;
	public const STONE_BRICKS = 10500;
	public const STONE_BUTTON = 10501;
	public const STONE_PRESSURE_PLATE = 10502;
	public const STONE_SLAB = 10503;
	public const STONE_STAIRS = 10504;
	public const STONECUTTER = 10505;

	public const SUGARCANE = 10518;
	public const SUNFLOWER = 10519;
	public const SWEET_BERRY_BUSH = 10520;
	public const TALL_GRASS = 10521;
	public const TNT = 10522;
	public const TORCH = 10523;
	public const TRAPPED_CHEST = 10524;
	public const TRIPWIRE = 10525;
	public const TRIPWIRE_HOOK = 10526;
	public const UNDERWATER_TORCH = 10527;
	public const VINES = 10528;
	public const WALL_BANNER = 10529;
	public const WALL_CORAL_FAN = 10530;
	public const WATER = 10531;
	public const WEIGHTED_PRESSURE_PLATE_HEAVY = 10532;
	public const WEIGHTED_PRESSURE_PLATE_LIGHT = 10533;
	public const WHEAT = 10534;

	public const WHITE_TULIP = 10536;
	public const WOOL = 10537;

	public const GLAZED_TERRACOTTA = 10539;
	public const AMETHYST = 10540;
	public const ANCIENT_DEBRIS = 10541;
	public const BASALT = 10542;
	public const POLISHED_BASALT = 10543;
	public const SMOOTH_BASALT = 10544;
	public const BLACKSTONE = 10545;
	public const BLACKSTONE_SLAB = 10546;
	public const BLACKSTONE_STAIRS = 10547;
	public const BLACKSTONE_WALL = 10548;
	public const POLISHED_BLACKSTONE = 10549;
	public const POLISHED_BLACKSTONE_BUTTON = 10550;
	public const POLISHED_BLACKSTONE_PRESSURE_PLATE = 10551;
	public const POLISHED_BLACKSTONE_SLAB = 10552;
	public const POLISHED_BLACKSTONE_STAIRS = 10553;
	public const POLISHED_BLACKSTONE_WALL = 10554;
	public const CHISELED_POLISHED_BLACKSTONE = 10555;
	public const POLISHED_BLACKSTONE_BRICKS = 10556;
	public const POLISHED_BLACKSTONE_BRICK_SLAB = 10557;
	public const POLISHED_BLACKSTONE_BRICK_STAIRS = 10558;
	public const POLISHED_BLACKSTONE_BRICK_WALL = 10559;
	public const CRACKED_POLISHED_BLACKSTONE_BRICKS = 10560;
	public const LIGHT = 10561;
	public const RAW_COPPER = 10562;
	public const RAW_GOLD = 10563;
	public const RAW_IRON = 10564;
	public const CALCITE = 10565;
	public const DEEPSLATE = 10566;
	public const DEEPSLATE_BRICKS = 10567;
	public const DEEPSLATE_BRICK_SLAB = 10568;
	public const DEEPSLATE_BRICK_STAIRS = 10569;
	public const DEEPSLATE_BRICK_WALL = 10570;
	public const CRACKED_DEEPSLATE_BRICKS = 10571;
	public const DEEPSLATE_TILES = 10572;
	public const DEEPSLATE_TILE_SLAB = 10573;
	public const DEEPSLATE_TILE_STAIRS = 10574;
	public const DEEPSLATE_TILE_WALL = 10575;
	public const CRACKED_DEEPSLATE_TILES = 10576;
	public const COBBLED_DEEPSLATE = 10577;
	public const COBBLED_DEEPSLATE_SLAB = 10578;
	public const COBBLED_DEEPSLATE_STAIRS = 10579;
	public const COBBLED_DEEPSLATE_WALL = 10580;
	public const POLISHED_DEEPSLATE = 10581;
	public const POLISHED_DEEPSLATE_SLAB = 10582;
	public const POLISHED_DEEPSLATE_STAIRS = 10583;
	public const POLISHED_DEEPSLATE_WALL = 10584;
	public const QUARTZ_BRICKS = 10585;
	public const CHISELED_DEEPSLATE = 10586;
	public const CHISELED_NETHER_BRICKS = 10587;
	public const CRACKED_NETHER_BRICKS = 10588;
	public const TUFF = 10589;
	public const SOUL_TORCH = 10590;
	public const SOUL_LANTERN = 10591;
	public const SOUL_SOIL = 10592;
	public const SOUL_FIRE = 10593;
	public const SHROOMLIGHT = 10594;
	public const MANGROVE_PLANKS = 10595;
	public const CRIMSON_PLANKS = 10596;
	public const WARPED_PLANKS = 10597;
	public const MANGROVE_FENCE = 10598;
	public const CRIMSON_FENCE = 10599;
	public const WARPED_FENCE = 10600;
	public const MANGROVE_SLAB = 10601;
	public const CRIMSON_SLAB = 10602;
	public const WARPED_SLAB = 10603;
	public const MANGROVE_LOG = 10604;
	public const CRIMSON_STEM = 10605;
	public const WARPED_STEM = 10606;
	public const MANGROVE_WOOD = 10607;
	public const CRIMSON_HYPHAE = 10608;
	public const WARPED_HYPHAE = 10609;
	public const MANGROVE_TRAPDOOR = 10610;
	public const CRIMSON_TRAPDOOR = 10611;
	public const WARPED_TRAPDOOR = 10612;
	public const MANGROVE_BUTTON = 10613;
	public const CRIMSON_BUTTON = 10614;
	public const WARPED_BUTTON = 10615;
	public const MANGROVE_PRESSURE_PLATE = 10616;
	public const CRIMSON_PRESSURE_PLATE = 10617;
	public const WARPED_PRESSURE_PLATE = 10618;
	public const MANGROVE_DOOR = 10619;
	public const CRIMSON_DOOR = 10620;
	public const WARPED_DOOR = 10621;
	public const MANGROVE_FENCE_GATE = 10622;
	public const CRIMSON_FENCE_GATE = 10623;
	public const WARPED_FENCE_GATE = 10624;
	public const MANGROVE_STAIRS = 10625;
	public const CRIMSON_STAIRS = 10626;
	public const WARPED_STAIRS = 10627;
	public const MANGROVE_SIGN = 10628;
	public const CRIMSON_SIGN = 10629;
	public const WARPED_SIGN = 10630;
	public const MANGROVE_WALL_SIGN = 10631;
	public const CRIMSON_WALL_SIGN = 10632;
	public const WARPED_WALL_SIGN = 10633;
	public const TINTED_GLASS = 10634;
	public const HONEYCOMB = 10635;
	public const DEEPSLATE_COAL_ORE = 10636;
	public const DEEPSLATE_DIAMOND_ORE = 10637;
	public const DEEPSLATE_EMERALD_ORE = 10638;
	public const DEEPSLATE_LAPIS_LAZULI_ORE = 10639;
	public const DEEPSLATE_REDSTONE_ORE = 10640;
	public const DEEPSLATE_IRON_ORE = 10641;
	public const DEEPSLATE_GOLD_ORE = 10642;
	public const DEEPSLATE_COPPER_ORE = 10643;
	public const COPPER_ORE = 10644;
	public const NETHER_GOLD_ORE = 10645;
	public const MUD = 10646;
	public const MUD_BRICKS = 10647;
	public const MUD_BRICK_SLAB = 10648;
	public const MUD_BRICK_STAIRS = 10649;
	public const MUD_BRICK_WALL = 10650;
	public const PACKED_MUD = 10651;
	public const WARPED_WART_BLOCK = 10652;
	public const CRYING_OBSIDIAN = 10653;
	public const GILDED_BLACKSTONE = 10654;
	public const LIGHTNING_ROD = 10655;
	public const COPPER = 10656;
	public const CUT_COPPER = 10657;
	public const CUT_COPPER_SLAB = 10658;
	public const CUT_COPPER_STAIRS = 10659;
	public const CANDLE = 10660;
	public const DYED_CANDLE = 10661;
	public const CAKE_WITH_CANDLE = 10662;
	public const CAKE_WITH_DYED_CANDLE = 10663;
	public const WITHER_ROSE = 10664;
	public const HANGING_ROOTS = 10665;
	public const CARTOGRAPHY_TABLE = 10666;
	public const SMITHING_TABLE = 10667;
	public const NETHERITE = 10668;
	public const SPORE_BLOSSOM = 10669;
	public const CAULDRON = 10670;
	public const WATER_CAULDRON = 10671;
	public const LAVA_CAULDRON = 10672;
	public const POTION_CAULDRON = 10673;
	public const POWDER_SNOW_CAULDRON = 10674;
	public const CHORUS_FLOWER = 10675;
	public const CHORUS_PLANT = 10676;
	public const MANGROVE_ROOTS = 10677;
	public const MUDDY_MANGROVE_ROOTS = 10678;
	public const FROGLIGHT = 10679;
	public const TWISTING_VINES = 10680;
	public const WEEPING_VINES = 10681;
	public const CHAIN = 10682;
	public const SCULK = 10683;
	public const GLOWING_ITEM_FRAME = 10684;
	public const MANGROVE_LEAVES = 10685;
	public const AZALEA_LEAVES = 10686;
	public const FLOWERING_AZALEA_LEAVES = 10687;
	public const REINFORCED_DEEPSLATE = 10688;
	public const CAVE_VINES = 10689;
	public const GLOW_LICHEN = 10690;
	public const CHERRY_BUTTON = 10691;
	public const CHERRY_DOOR = 10692;
	public const CHERRY_FENCE = 10693;
	public const CHERRY_FENCE_GATE = 10694;
	public const CHERRY_LEAVES = 10695;
	public const CHERRY_LOG = 10696;
	public const CHERRY_PLANKS = 10697;
	public const CHERRY_PRESSURE_PLATE = 10698;
	public const CHERRY_SAPLING = 10699;
	public const CHERRY_SIGN = 10700;
	public const CHERRY_SLAB = 10701;
	public const CHERRY_STAIRS = 10702;
	public const CHERRY_TRAPDOOR = 10703;
	public const CHERRY_WALL_SIGN = 10704;
	public const CHERRY_WOOD = 10705;
	public const CHISELED_BOOKSHELF = 10706;

	public const FIRST_UNUSED_BLOCK_ID = 10707;

	private static int $nextDynamicId = self::FIRST_UNUSED_BLOCK_ID;

	/**
	 * Returns a new runtime block type ID, e.g. for use by a custom block.
	 */
	public static function newId() : int{
		return self::$nextDynamicId++;
	}
}
