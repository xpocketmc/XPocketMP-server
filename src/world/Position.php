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

namespace pocketmine\world;

use pocketmine\math\Vector3;
use pocketmine\utils\AssumptionFailedError;
use function assert;

class Position extends Vector3{

	/** @var World|null */
	public $world = null;

	/**
	 * @param float|int $x
	 * @param float|int $y
	 * @param float|int $z
	 */
	public function __construct($x = 0, $y = 0, $z = 0, ?World $world = null){
		parent::__construct($x, $y, $z);
		if($world !== null and $world->isClosed()){
			throw new \InvalidArgumentException("Specified world has been unloaded and cannot be used");
		}

		$this->world = $world;
	}

	/**
	 * @return Position
	 */
	public static function fromObject(Vector3 $pos, ?World $world = null){
		return new Position($pos->x, $pos->y, $pos->z, $world);
	}

	/**
	 * Return a Position instance
	 */
	public function asPosition() : Position{
		return new Position($this->x, $this->y, $this->z, $this->world);
	}

	/**
	 * Returns the target world, or null if the target is not valid.
	 * If a reference exists to a world which is closed, the reference will be destroyed and null will be returned.
	 *
	 * @return World|null
	 */
	public function getWorld(){
		if($this->world !== null and $this->world->isClosed()){
			\GlobalLogger::get()->debug("Position was holding a reference to an unloaded world");
			$this->world = null;
		}

		return $this->world;
	}

	/**
	 * Returns the position's world if valid. Throws an error if the world is unexpectedly null.
	 *
	 * @throws AssumptionFailedError
	 */
	public function getWorldNonNull() : World{
		$world = $this->getWorld();
		if($world === null){
			throw new AssumptionFailedError("Position world is null");
		}

		return $world;
	}

	/**
	 * Checks if this object has a valid reference to a loaded world
	 */
	public function isValid() : bool{
		if($this->world !== null and $this->world->isClosed()){
			$this->world = null;

			return false;
		}

		return $this->world !== null;
	}

	/**
	 * Returns a side Vector
	 *
	 * @return Position
	 */
	public function getSide(int $side, int $step = 1){
		assert($this->isValid());

		return Position::fromObject(parent::getSide($side, $step), $this->world);
	}

	public function __toString(){
		return "Position(level=" . ($this->isValid() ? $this->getWorldNonNull()->getDisplayName() : "null") . ",x=" . $this->x . ",y=" . $this->y . ",z=" . $this->z . ")";
	}

	public function equals(Vector3 $v) : bool{
		if($v instanceof Position){
			return parent::equals($v) and $v->getWorld() === $this->getWorld();
		}
		return parent::equals($v);
	}
}
