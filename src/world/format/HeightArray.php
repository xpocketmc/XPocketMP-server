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

namespace pocketmine\world\format;

use function array_fill;
use function count;

final class HeightArray{

	/**
	 * @var \SplFixedArray|int[]
	 * @phpstan-var \SplFixedArray<int>
	 */
	private \SplFixedArray $array;

	/**
	 * @param int[] $values ZZZZXXXX key bit order
	 * @phpstan-param list<int> $values
	 */
	public function __construct(array $values){
		if(count($values) !== 256){
			throw new \InvalidArgumentException("Expected exactly 256 values");
		}
		$this->array = \SplFixedArray::fromArray($values);
	}

	public static function fill(int $value) : self{
		return new self(array_fill(0, 256, $value));
	}

	private static function idx(int $x, int $z) : int{
		if($x < 0 || $x >= 16 || $z < 0 || $z >= 16){
			throw new \InvalidArgumentException("x and z must be in the range 0-15");
		}
		return ($z << 4) | $x;
	}

	public function get(int $x, int $z) : int{
		return $this->array[self::idx($x, $z)];
	}

	public function set(int $x, int $z, int $height) : void{
		$this->array[self::idx($x, $z)] = $height;
	}

	/**
	 * @return int[] ZZZZXXXX key bit order
	 * @phpstan-return list<int>
	 */
	public function getValues() : array{
		return $this->array->toArray();
	}

	public function __clone(){
		$this->array = clone $this->array;
	}
}
