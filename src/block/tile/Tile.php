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
 * @author XPocketMP Team
 * @link http://www.xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

/**
 * All the Tile classes and related classes
 */

namespace XPocketMPlock\tile;

use XPocketMPlock\Block;
use XPocketMP\item\Item;
use XPocketMP\math\Vector3;
use XPocketMP\nbt\NbtDataException;
use XPocketMP\nbt\tag\CompoundTag;
use XPocketMP\timings\Timings;
use XPocketMP\timings\TimingsHandler;
use XPocketMP\VersionInfo;
use XPocketMP\world\Position;
use XPocketMP\world\World;
use function get_class;

abstract class Tile{

	public const TAG_ID = "id";
	public const TAG_X = "x";
	public const TAG_Y = "y";
	public const TAG_Z = "z";

	protected Position $position;
	public bool $closed = false;
	protected TimingsHandler $timings;

	public function __construct(World $world, Vector3 $pos){
		$this->position = Position::fromObject($pos, $world);
		$this->timings = Timings::getTileEntityTimings($this);
	}

	/**
	 * @internal
	 * @throws NbtDataException
	 * Reads additional data from the CompoundTag on tile creation.
	 */
	abstract public function readSaveData(CompoundTag $nbt) : void;

	/**
	 * Writes additional save data to a CompoundTag, not including generic things like ID and coordinates.
	 */
	abstract protected function writeSaveData(CompoundTag $nbt) : void;

	public function saveNBT() : CompoundTag{
		$nbt = CompoundTag::create()
			->setString(self::TAG_ID, TileFactory::getInstance()->getSaveId(get_class($this)))
			->setInt(self::TAG_X, $this->position->getFloorX())
			->setInt(self::TAG_Y, $this->position->getFloorY())
			->setInt(self::TAG_Z, $this->position->getFloorZ())
			->setLong(VersionInfo::TAG_WORLD_DATA_VERSION, VersionInfo::WORLD_DATA_VERSION);
		$this->writeSaveData($nbt);

		return $nbt;
	}

	public function getCleanedNBT() : ?CompoundTag{
		$this->writeSaveData($tag = new CompoundTag());
		return $tag->getCount() > 0 ? $tag : null;
	}

	/**
	 * @internal
	 *
	 * @throws \RuntimeException
	 */
	public function copyDataFromItem(Item $item) : void{
		if(($blockNbt = $item->getCustomBlockData()) !== null){ //TODO: check item root tag (MCPE doesn't use BlockEntityTag)
			$this->readSaveData($blockNbt);
		}
	}

	public function getBlock() : Block{
		return $this->position->getWorld()->getBlock($this->position);
	}

	public function getPosition() : Position{
		return $this->position;
	}

	public function isClosed() : bool{
		return $this->closed;
	}

	public function __destruct(){
		$this->close();
	}

	/**
	 * Called when the tile's block is destroyed.
	 */
	final public function onBlockDestroyed() : void{
		$this->onBlockDestroyedHook();
		$this->close();
	}

	/**
	 * Override this method to do actions you need to do when this tile is destroyed due to block being broken.
	 */
	protected function onBlockDestroyedHook() : void{

	}

	public function close() : void{
		if(!$this->closed){
			$this->closed = true;

			if($this->position->isValid()){
				$this->position->getWorld()->removeTile($this);
			}
		}
	}
}