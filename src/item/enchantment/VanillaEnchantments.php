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

namespace pocketmine\item\enchantment;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\utils\RegistryTrait;
use function array_key_exists;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see \pocketmine\utils\RegistryUtils::_generateMethodAnnotations()
 *
 * @method static ProtectionEnchantment PROTECTION()
 * @method static ProtectionEnchantment FIRE_PROTECTION()
 * @method static ProtectionEnchantment FEATHER_FALLING()
 * @method static ProtectionEnchantment BLAST_PROTECTION()
 * @method static ProtectionEnchantment PROJECTILE_PROTECTION()
 * @method static Enchantment THORNS()
 * @method static Enchantment RESPIRATION()
 * @method static SharpnessEnchantment SHARPNESS()
 * @method static KnockbackEnchantment KNOCKBACK()
 * @method static FireAspectEnchantment FIRE_ASPECT()
 * @method static Enchantment EFFICIENCY()
 * @method static Enchantment SILK_TOUCH()
 * @method static Enchantment UNBREAKING()
 * @method static Enchantment POWER()
 * @method static Enchantment PUNCH()
 * @method static Enchantment FLAME()
 * @method static Enchantment INFINITY()
 * @method static Enchantment MENDING()
 * @method static Enchantment VANISHING()
 */
final class VanillaEnchantments{
	use RegistryTrait;

	/**
	 * @var Enchantment[]
	 * @phpstan-var array<int, Enchantment>
	 */
	private static $mcpeIdMap = [];

	protected static function setup() : void{
		self::register("PROTECTION", new ProtectionEnchantment(EnchantmentIds::PROTECTION, "%enchantment.protect.all", Rarity::COMMON, ItemFlags::ARMOR, ItemFlags::NONE, 4, 0.75, null));
		self::register("FIRE_PROTECTION", new ProtectionEnchantment(EnchantmentIds::FIRE_PROTECTION, "%enchantment.protect.fire", Rarity::UNCOMMON, ItemFlags::ARMOR, ItemFlags::NONE, 4, 1.25, [
			EntityDamageEvent::CAUSE_FIRE,
			EntityDamageEvent::CAUSE_FIRE_TICK,
			EntityDamageEvent::CAUSE_LAVA
			//TODO: check fireballs
		]));
		self::register("FEATHER_FALLING", new ProtectionEnchantment(EnchantmentIds::FEATHER_FALLING, "%enchantment.protect.fall", Rarity::UNCOMMON, ItemFlags::FEET, ItemFlags::NONE, 4, 2.5, [
			EntityDamageEvent::CAUSE_FALL
		]));
		self::register("BLAST_PROTECTION", new ProtectionEnchantment(EnchantmentIds::BLAST_PROTECTION, "%enchantment.protect.explosion", Rarity::RARE, ItemFlags::ARMOR, ItemFlags::NONE, 4, 1.5, [
			EntityDamageEvent::CAUSE_BLOCK_EXPLOSION,
			EntityDamageEvent::CAUSE_ENTITY_EXPLOSION
		]));
		self::register("PROJECTILE_PROTECTION", new ProtectionEnchantment(EnchantmentIds::PROJECTILE_PROTECTION, "%enchantment.protect.projectile", Rarity::UNCOMMON, ItemFlags::ARMOR, ItemFlags::NONE, 4, 1.5, [
			EntityDamageEvent::CAUSE_PROJECTILE
		]));
		self::register("THORNS", new Enchantment(EnchantmentIds::THORNS, "%enchantment.thorns", Rarity::MYTHIC, ItemFlags::TORSO, ItemFlags::HEAD | ItemFlags::LEGS | ItemFlags::FEET, 3));
		self::register("RESPIRATION", new Enchantment(EnchantmentIds::RESPIRATION, "%enchantment.oxygen", Rarity::RARE, ItemFlags::HEAD, ItemFlags::NONE, 3));

		self::register("SHARPNESS", new SharpnessEnchantment(EnchantmentIds::SHARPNESS, "%enchantment.damage.all", Rarity::COMMON, ItemFlags::SWORD, ItemFlags::AXE, 5));
		//TODO: smite, bane of arthropods (these don't make sense now because their applicable mobs don't exist yet)

		self::register("KNOCKBACK", new KnockbackEnchantment(EnchantmentIds::KNOCKBACK, "%enchantment.knockback", Rarity::UNCOMMON, ItemFlags::SWORD, ItemFlags::NONE, 2));
		self::register("FIRE_ASPECT", new FireAspectEnchantment(EnchantmentIds::FIRE_ASPECT, "%enchantment.fire", Rarity::RARE, ItemFlags::SWORD, ItemFlags::NONE, 2));

		self::register("EFFICIENCY", new Enchantment(EnchantmentIds::EFFICIENCY, "%enchantment.digging", Rarity::COMMON, ItemFlags::DIG, ItemFlags::SHEARS, 5));
		self::register("SILK_TOUCH", new Enchantment(EnchantmentIds::SILK_TOUCH, "%enchantment.untouching", Rarity::MYTHIC, ItemFlags::DIG, ItemFlags::SHEARS, 1));
		self::register("UNBREAKING", new Enchantment(EnchantmentIds::UNBREAKING, "%enchantment.durability", Rarity::UNCOMMON, ItemFlags::DIG | ItemFlags::ARMOR | ItemFlags::FISHING_ROD | ItemFlags::BOW, ItemFlags::TOOL | ItemFlags::CARROT_STICK | ItemFlags::ELYTRA, 3));

		self::register("POWER", new Enchantment(EnchantmentIds::POWER, "%enchantment.arrowDamage", Rarity::COMMON, ItemFlags::BOW, ItemFlags::NONE, 5));
		self::register("PUNCH", new Enchantment(EnchantmentIds::PUNCH, "%enchantment.arrowKnockback", Rarity::RARE, ItemFlags::BOW, ItemFlags::NONE, 2));
		self::register("FLAME", new Enchantment(EnchantmentIds::FLAME, "%enchantment.arrowFire", Rarity::RARE, ItemFlags::BOW, ItemFlags::NONE, 1));
		self::register("INFINITY", new Enchantment(EnchantmentIds::INFINITY, "%enchantment.arrowInfinite", Rarity::MYTHIC, ItemFlags::BOW, ItemFlags::NONE, 1));

		self::register("MENDING", new Enchantment(EnchantmentIds::MENDING, "%enchantment.mending", Rarity::RARE, ItemFlags::NONE, ItemFlags::ALL, 1));

		self::register("VANISHING", new Enchantment(EnchantmentIds::VANISHING, "%enchantment.curse.vanishing", Rarity::MYTHIC, ItemFlags::NONE, ItemFlags::ALL, 1));
	}

	protected static function register(string $name, Enchantment $member) : void{
		if(array_key_exists($member->getId(), self::$mcpeIdMap)){
			throw new \InvalidArgumentException("MCPE enchantment ID " . $member->getId() . " is already assigned");
		}
		self::_registryRegister($name, $member);
		self::$mcpeIdMap[$member->getId()] = $member;
	}

	public static function byMcpeId(int $id) : ?Enchantment{
		//TODO: this shouldn't be in here, it's unnecessarily limiting
		self::checkInit();
		return self::$mcpeIdMap[$id] ?? null;
	}

	/**
	 * @return Enchantment[]
	 * @phpstan-return array<string, Enchantment>
	 */
	public static function getAll() : array{
		/**
		 * @var Enchantment[] $result
		 * @phpstan-var array<string, Enchantment> $result
		 */
		$result = self::_registryGetAll();
		return $result;
	}

	public static function fromString(string $name) : Enchantment{
		/** @var Enchantment $result */
		$result = self::_registryFromString($name);
		return $result;
	}
}
