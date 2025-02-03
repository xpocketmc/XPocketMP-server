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

use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\Binary;
use pocketmine\world\format\io\ChunkData;
use pocketmine\world\format\io\exception\CorruptedChunkException;
use pocketmine\world\format\io\LoadedChunkData;
use pocketmine\world\WorldCreationOptions;
use Symfony\Component\Filesystem\Path;
use function array_key_exists;
use function file_exists;
use function floor;
use function is_dir;
use function mkdir;
use function morton2d_decode;
use function morton2d_encode;
use function sprintf;
use function str_contains;
use function time;
use function touch;
use const LEVELDB_ZLIB_RAW_COMPRESSION;

final class RegionizedLevelDB extends BaseLevelDB{

	/**
	 * Any DBs that haven't been accessed for the last 5 minutes will be removed from memory
	 */
	private const MAX_DB_CACHE_AGE = 5 * 60;

	private const DB_DEFAULT_OPTIONS = [
		"compression" => LEVELDB_ZLIB_RAW_COMPRESSION,
		"block_size" => 64 * 1024 //64KB, big enough for most chunks
	];

	private static function dbRegionPath(string $base, int $regionLength) : string{
		return Path::join($base, "leveldb-regions-$regionLength");
	}

	public static function isValid(string $path, int $regionLength) : bool{
		return file_exists(Path::join($path, "level.dat")) && is_dir(self::dbRegionPath($path, $regionLength));
	}

	public static function generate(string $path, string $name, WorldCreationOptions $options, int $regionLength) : void{
		self::baseGenerate($path, $name, $options);
		touch(Path::join($path, 'NOT_BEDROCK_COMPATIBLE.txt'));

		@mkdir(self::dbRegionPath($path, $regionLength), 0777, true);
	}

	/**
	 * @var \LevelDB[]|null[]
	 * @phpstan-var array<int, \LevelDB|null>
	 */
	private array $databases = [];

	/**
	 * @var int[]
	 * @phpstan-var array<int, int>
	 */
	private array $databasesLastUsed = [];

	public function __construct(
		string $path,
		\Logger $logger,
		private readonly int $regionLength
	){
		parent::__construct($path, $logger);
	}

	protected function coordsFromChunkIndex(string $chunkIndex) : array{
		//TODO: these indexes don't need to use long in separated DBs, we could make them smaller and save space
		/**
		 * @var int[] $decoded
		 * @phpstan-var array{int, int} $decoded
		 */
		$decoded = morton2d_decode(Binary::readLong($chunkIndex));
		return $decoded;
	}

	protected function coordsToChunkIndex(int $chunkX, int $chunkZ) : string{
		return Binary::writeLong(morton2d_encode($chunkX, $chunkZ));
	}

	protected function getDBPathForRegionCoords(int $regionX, int $regionZ) : string{
		return Path::join(self::dbRegionPath($this->path, $this->regionLength), sprintf(
			"db.%d.%d",
			$regionX,
			$regionZ
		));
	}

	/**
	 * @throws CorruptedChunkException
	 */
	protected function fetchDBForRegionCoords(int $regionX, int $regionZ, bool $createIfMissing) : ?\LevelDB{
		$index = morton2d_encode($regionX, $regionZ);
		$db = $this->databases[$index] ?? null;

		if(
			!array_key_exists($index, $this->databases) || //we haven't tried to fetch this DB yet
			($db === null && $createIfMissing) //or we know it doesn't exist and want to create it (for writing)
		){
			$options = self::DB_DEFAULT_OPTIONS;
			$options["create_if_missing"] = $createIfMissing;
			$dbPath = $this->getDBPathForRegionCoords($regionX, $regionZ);
			try{
				$this->databases[$index] = new \LevelDB($dbPath, $options);
			}catch(\LevelDBException $e){
				//no other way to detect error type :(
				if(!str_contains($e->getMessage(), "(create_if_missing is false)")){
					throw new CorruptedChunkException("Couldn't open LevelDB region $dbPath: " . $e->getMessage(), 0, $e);
				}

				//remember that this DB doesn't exist, so we don't have to hit the disk hundreds of times looking for it
				$this->databases[$index] = null;
			}
		}

		$this->databasesLastUsed[$index] = time();
		return $this->databases[$index];

	}

	protected function fetchDBForChunkCoords(int $chunkX, int $chunkZ, bool $createIfMissing) : ?\LevelDB{
		return $this->fetchDBForRegionCoords(
			(int) floor($chunkX / $this->regionLength),
			(int) floor($chunkZ / $this->regionLength),
			$createIfMissing
		);
	}

	public function loadChunk(int $chunkX, int $chunkZ) : ?LoadedChunkData{
		$db = $this->fetchDBForChunkCoords($chunkX, $chunkZ, createIfMissing: false);
		return $db !== null ? $this->loadChunkFromDB($db, $chunkX, $chunkZ) : null;
	}

	public function saveChunk(int $chunkX, int $chunkZ, ChunkData $chunkData, int $dirtyFlags) : void{
		$db = $this->fetchDBForChunkCoords($chunkX, $chunkZ, createIfMissing: true) ??
			throw new AssumptionFailedError("We asked fetch to create a DB, it shouldn't return null");

		$this->saveChunkToDB($db, $chunkX, $chunkZ, $chunkData, $dirtyFlags);
	}

	public function doGarbageCollection() : void{
		$minLastUsed = time() - self::MAX_DB_CACHE_AGE;
		foreach($this->databasesLastUsed as $index => $time){
			if($time < $minLastUsed){
				//unset will close the DB
				unset(
					$this->databases[$index],
					$this->databasesLastUsed[$index]
				);
			}
		}
	}

	public function close() : void{
		//no explicit actions needed to close DBs
		$this->databases = [];
		$this->databasesLastUsed = [];
	}

	private function createRegionIterator() : \RegexIterator{
		return new \RegexIterator(
			new \FilesystemIterator(
				self::dbRegionPath($this->path, $this->regionLength),
				\FilesystemIterator::CURRENT_AS_PATHNAME | \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS
			),
			'/\/db\.(-?\d+)\.(-?\d+)$/',
			\RegexIterator::GET_MATCH
		);
	}

	public function getAllChunks(bool $skipCorrupted = false, ?\Logger $logger = null) : \Generator{
		$iterator = $this->createRegionIterator();

		/** @var string[] $region */
		foreach($iterator as $region){
			try{
				$regionX = (int) $region[1];
				$regionZ = (int) $region[2];
				$db = $this->fetchDBForRegionCoords($regionX, $regionZ, createIfMissing: false);
				if($db === null){
					throw new AssumptionFailedError("DB at $region[0] should definitely exist");
				}

				//TODO: we don't need the DB name coords for now, but we might in the future if the key format is
				//changed to be relative
				yield from $this->getAllChunksFromDB($db, $skipCorrupted, $logger);
			}catch(CorruptedChunkException $e){
				//TODO: detect permission errors - although I'm not sure what we could do differently
				if(!$skipCorrupted){
					throw $e;
				}
				if($logger !== null){
					$logger->error($e->getMessage());
				}
			}
		}
	}

	public function calculateChunkCount() : int{
		$iterator = $this->createRegionIterator();

		$total = 0;
		/** @var string[] $region */
		foreach($iterator as $region){
			//TODO: calculateChunkCount has no accounting for corruption errors
			$regionX = (int) $region[1];
			$regionZ = (int) $region[2];
			$db = $this->fetchDBForRegionCoords($regionX, $regionZ, createIfMissing: false);
			if($db === null){
				throw new AssumptionFailedError("DB at $region[0] should definitely exist");
			}

			//TODO: we'd need a specialized calculate impl if we change the key length
			$total += $this->calculateChunkCountInDB($db);
		}

		return $total;
	}
}
