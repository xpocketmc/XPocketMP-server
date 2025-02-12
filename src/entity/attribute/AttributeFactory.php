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

namespace pocketmine\entity\attribute;

use pocketmine\utils\SingletonTrait;

final class AttributeFactory{
	use SingletonTrait;

	/** @var AttributeValue[] */
	private array $attributes = [];

	public function __construct(){
		$this->register(AttributeType::ABSORPTION, 0.00, 340282346638528859811704183484516925440.00, 0.00);
		$this->register(AttributeType::SATURATION, 0.00, 20.00, 20.00);
		$this->register(AttributeType::EXHAUSTION, 0.00, 5.00, 0.0, false);
		$this->register(AttributeType::KNOCKBACK_RESISTANCE, 0.00, 1.00, 0.00);
		$this->register(AttributeType::HEALTH, 0.00, 20.00, 20.00);
		$this->register(AttributeType::MOVEMENT_SPEED, 0.00, 340282346638528859811704183484516925440.00, 0.10);
		$this->register(AttributeType::FOLLOW_RANGE, 0.00, 2048.00, 16.00, false);
		$this->register(AttributeType::HUNGER, 0.00, 20.00, 20.00);
		$this->register(AttributeType::ATTACK_DAMAGE, 0.00, 340282346638528859811704183484516925440.00, 1.00, false);
		$this->register(AttributeType::EXPERIENCE_LEVEL, 0.00, 24791.00, 0.00);
		$this->register(AttributeType::EXPERIENCE, 0.00, 1.00, 0.00);
		$this->register(AttributeType::UNDERWATER_MOVEMENT, 0.0, 340282346638528859811704183484516925440.0, 0.02);
		$this->register(AttributeType::LUCK, -1024.0, 1024.0, 0.0);
		$this->register(AttributeType::FALL_DAMAGE, 0.0, 340282346638528859811704183484516925440.0, 1.0);
		$this->register(AttributeType::HORSE_JUMP_STRENGTH, 0.0, 2.0, 0.7);
		$this->register(AttributeType::ZOMBIE_SPAWN_REINFORCEMENTS, 0.0, 1.0, 0.0);
		$this->register(AttributeType::LAVA_MOVEMENT, 0.0, 340282346638528859811704183484516925440.0, 0.02);
	}

	public function get(string $id) : ?AttributeValue{
		return isset($this->attributes[$id]) ? clone $this->attributes[$id] : null;
	}

	public function mustGet(string $id) : AttributeValue{
		$result = $this->get($id);
		if($result === null){
			throw new \InvalidArgumentException("Attribute $id is not registered");
		}
		return $result;
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	public function register(string $id, float $minValue, float $maxValue, float $defaultValue, bool $shouldSend = true) : AttributeValue{
		return $this->attributes[$id] = new Attribute($id, $minValue, $maxValue, $defaultValue, $shouldSend);
	}
}
