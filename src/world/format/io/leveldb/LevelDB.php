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

namespace pocketmine\world\format\io\leveldb;

use pocketmine\utils\Binary;
use pocketmine\world\format\io\ChunkData;
use pocketmine\world\format\io\exception\CorruptedChunkException;
use pocketmine\world\format\io\exception\CorruptedWorldException;
use pocketmine\world\format\io\LoadedChunkData;
use pocketmine\world\WorldCreationOptions;
use Symfony\Component\Filesystem\Path;
use function file_exists;
use function is_dir;
use function mkdir;
use function substr;
use function trim;
use const LEVELDB_ZLIB_RAW_COMPRESSION;

class LevelDB extends BaseLevelDB{

	protected \LevelDB $db;

	/**
	 * @throws \LevelDBException
	 */
	private static function createDB(string $path) : \LevelDB{
		return new \LevelDB(Path::join($path, "db"), [
			"compression" => LEVELDB_ZLIB_RAW_COMPRESSION,
			"block_size" => 64 * 1024 //64KB, big enough for most chunks
		]);
	}

	public function __construct(string $path, \Logger $logger){
		parent::__construct($path, $logger);

		try{
			$this->db = self::createDB($path);
		}catch(\LevelDBException $e){
			//we can't tell the difference between errors caused by bad permissions and actual corruption :(
			throw new CorruptedWorldException(trim($e->getMessage()), 0, $e);
		}
	}

	public static function isValid(string $path) : bool{
		return file_exists(Path::join($path, "level.dat")) && is_dir(Path::join($path, "db"));
	}

	public static function generate(string $path, string $name, WorldCreationOptions $options) : void{
		self::baseGenerate($path, $name, $options);

		$dbPath = Path::join($path, "db");
		if(!file_exists($dbPath)){
			mkdir($dbPath, 0777, true);
		}
	}

	/**
	 * @throws CorruptedChunkException
	 */
	public function loadChunk(int $chunkX, int $chunkZ) : ?LoadedChunkData{
		return $this->loadChunkFromDB($this->db, $chunkX, $chunkZ);
	}

	public function saveChunk(int $chunkX, int $chunkZ, ChunkData $chunkData, int $dirtyFlags) : void{
		$this->saveChunkToDB($this->db, $chunkX, $chunkZ, $chunkData, $dirtyFlags);
	}

	public function getDatabase() : \LevelDB{
		return $this->db;
	}

	/**
	 * @deprecated
	 */
	public static function chunkIndex(int $chunkX, int $chunkZ) : string{
		return Binary::writeLInt($chunkX) . Binary::writeLInt($chunkZ);
	}

	protected function coordsToChunkIndex(int $chunkX, int $chunkZ) : string{
		return Binary::writeLInt($chunkX) . Binary::writeLInt($chunkZ);
	}

	/**
	 * @return int[]
	 * @phpstan-return array{int, int}
	 */
	protected function coordsFromChunkIndex(string $chunkIndex) : array{
		return [Binary::readLInt(substr($chunkIndex, 0, 4)), Binary::readLInt(substr($chunkIndex, 4, 4))];
	}

	public function doGarbageCollection() : void{

	}

	public function close() : void{
		unset($this->db);
	}

	public function getAllChunks(bool $skipCorrupted = false, ?\Logger $logger = null) : \Generator{
		yield from $this->getAllChunksFromDB($this->db, $skipCorrupted, $logger);
	}

	public function calculateChunkCount() : int{
		return $this->calculateChunkCountInDB($this->db);
	}
}
