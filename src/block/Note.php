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
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\tile\Note as TileNote;
use function assert;

class Note extends Opaque{
	public const MIN_PITCH = 0;
	public const MAX_PITCH = 24;

	private int $pitch = self::MIN_PITCH;

	public function readStateFromWorld() : Block{
		parent::readStateFromWorld();
		$tile = $this->position->getWorld()->getTile($this->position);
		if($tile instanceof TileNote){
			$this->pitch = $tile->getPitch();
		}else{
			$this->pitch = self::MIN_PITCH;
		}

		return $this;
	}

	public function writeStateToWorld() : void{
		parent::writeStateToWorld();
		$tile = $this->position->getWorld()->getTile($this->position);
		assert($tile instanceof TileNote);
		$tile->setPitch($this->pitch);
	}

	public function getFuelTime() : int{
		return 300;
	}

	public function getPitch() : int{
		return $this->pitch;
	}

	/** @return $this */
	public function setPitch(int $pitch) : self{
		if($pitch < self::MIN_PITCH || $pitch > self::MAX_PITCH){
			throw new \InvalidArgumentException("Pitch must be in range " . self::MIN_PITCH . " - " . self::MAX_PITCH);
		}
		$this->pitch = $pitch;
		return $this;
	}

	//TODO
}
