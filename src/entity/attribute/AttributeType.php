<?php

declare(strict_types=1);

namespace pocketmine\entity\attribute;

final class AttributeType {
    public const MC_PREFIX = "minecraft:";

    public const ABSORPTION = self::MC_PREFIX . "absorption";
    public const SATURATION = self::MC_PREFIX . "player.saturation";
    public const EXHAUSTION = self::MC_PREFIX . "player.exhaustion";
    public const KNOCKBACK_RESISTANCE = self::MC_PREFIX . "knockback_resistance";
    public const HEALTH = self::MC_PREFIX . "health";
    public const MOVEMENT_SPEED = self::MC_PREFIX . "movement";
    public const FOLLOW_RANGE = self::MC_PREFIX . "follow_range";
    public const HUNGER = self::MC_PREFIX . "player.hunger";
    public const FOOD = self::HUNGER;
    public const ATTACK_DAMAGE = self::MC_PREFIX . "attack_damage";
    public const EXPERIENCE_LEVEL = self::MC_PREFIX . "player.level";
    public const EXPERIENCE = self::MC_PREFIX . "player.experience";
    public const UNDERWATER_MOVEMENT = self::MC_PREFIX . "underwater_movement";
    public const LUCK = self::MC_PREFIX . "luck";
    public const FALL_DAMAGE = self::MC_PREFIX . "fall_damage";
    public const HORSE_JUMP_STRENGTH = self::MC_PREFIX . "horse.jump_strength";
    public const ZOMBIE_SPAWN_REINFORCEMENTS = self::MC_PREFIX . "zombie.spawn_reinforcements";
    public const LAVA_MOVEMENT = self::MC_PREFIX . "lava_movement";

    private function __construct() {}
}
