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

namespace pocketmine\entity\object;

class PaintingMotive{
	private static bool $initialized = false;

	/** @var PaintingMotive[] */
	protected static array $motives = [];

	public static function init() : void{
		self::$initialized = true;
		foreach([
			new PaintingMotive(1, 1, "Alban"),
			new PaintingMotive(1, 1, "Aztec"),
			new PaintingMotive(1, 1, "Aztec2"),
			new PaintingMotive(1, 1, "Bomb"),
			new PaintingMotive(1, 1, "Kebab"),
			new PaintingMotive(1, 1, "meditative"),
			new PaintingMotive(1, 1, "Plant"),
			new PaintingMotive(1, 1, "Wasteland"),
			new PaintingMotive(1, 2, "Graham"),
			new PaintingMotive(1, 2, "prairie_ride"),
			new PaintingMotive(1, 2, "Wanderer"),
			new PaintingMotive(2, 1, "Courbet"),
			new PaintingMotive(2, 1, "Creebet"),
			new PaintingMotive(2, 1, "Pool"),
			new PaintingMotive(2, 1, "Sea"),
			new PaintingMotive(2, 1, "Sunset"),
			new PaintingMotive(2, 2, "Bust"),
			new PaintingMotive(2, 2, "baroque"),
			new PaintingMotive(2, 2, "Earth"),
			new PaintingMotive(2, 2, "Fire"),
			new PaintingMotive(2, 2, "humble"),
			new PaintingMotive(2, 2, "Match"),
			new PaintingMotive(2, 2, "SkullAndRoses"),
			new PaintingMotive(2, 2, "Stage"),
			new PaintingMotive(2, 2, "Void"),
			new PaintingMotive(2, 2, "Water"),
			new PaintingMotive(2, 2, "Wind"),
			new PaintingMotive(2, 2, "Wither"),
			new PaintingMotive(3, 3, "bouquet"),
			new PaintingMotive(3, 3, "cavebird"),
			new PaintingMotive(3, 3, "cotan"),
			new PaintingMotive(3, 3, "endboss"),
			new PaintingMotive(3, 3, "fern"),
			new PaintingMotive(3, 3, "owlemons"),
			new PaintingMotive(3, 3, "sunflowers"),
			new PaintingMotive(3, 3, "tides"),
			new PaintingMotive(3, 4, "backyard"),
			new PaintingMotive(3, 4, "pond"),
			new PaintingMotive(4, 2, "changing"),
			new PaintingMotive(4, 2, "Fighters"),
			new PaintingMotive(4, 2, "finding"),
			new PaintingMotive(4, 2, "lowmist"),
			new PaintingMotive(4, 2, "passage"),
			new PaintingMotive(4, 3, "DonkeyKong"),
			new PaintingMotive(4, 3, "Skeleton"),
			new PaintingMotive(4, 4, "BurningSkull"),
			new PaintingMotive(4, 4, "orb"),
			new PaintingMotive(4, 4, "Pigscene"),
			new PaintingMotive(4, 4, "Pointer"),
			new PaintingMotive(4, 4, "unpacked")
		] as $motive){
			self::registerMotive($motive);
		}
	}

	public static function registerMotive(PaintingMotive $motive) : void{
		if(!self::$initialized){
			self::init();
		}
		self::$motives[$motive->getName()] = $motive;
	}

	public static function getMotiveByName(string $name) : ?PaintingMotive{
		if(!self::$initialized){
			self::init();
		}
		return self::$motives[$name] ?? null;
	}

	/**
	 * @return PaintingMotive[]
	 */
	public static function getAll() : array{
		if(!self::$initialized){
			self::init();
		}
		return self::$motives;
	}

	public function __construct(
		protected int $width,
		protected int $height,
		protected string $name
	){}

	public function getName() : string{
		return $this->name;
	}

	public function getWidth() : int{
		return $this->width;
	}

	public function getHeight() : int{
		return $this->height;
	}

	public function __toString() : string{
		return "PaintingMotive(name: " . $this->getName() . ", height: " . $this->getHeight() . ", width: " . $this->getWidth() . ")";
	}
}
