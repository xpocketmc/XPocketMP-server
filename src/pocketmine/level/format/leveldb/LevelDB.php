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

namespace pocketmine\level\format\leveldb;

use pocketmine\level\format\FullChunk;
use pocketmine\level\format\generic\BaseLevelProvider;
use pocketmine\level\generator\Generator;
use pocketmine\level\Level;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\Byte;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Int;
use pocketmine\nbt\tag\Long;
use pocketmine\nbt\tag\String;
use pocketmine\tile\Spawnable;
use pocketmine\utils\Binary;
use pocketmine\utils\ChunkException;
use pocketmine\utils\LevelException;

class LevelDB extends BaseLevelProvider{

	/** @var Chunk[] */
	protected $chunks = [];

	/** @var \LevelDB */
	protected $db;

	public function __construct(Level $level, $path){
		$this->level = $level;
		$this->path = $path;
		if(!file_exists($this->path)){
			mkdir($this->path, 0777, true);
		}
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		$nbt->read(substr(file_get_contents($this->getPath() . "level.dat"), 8));
		$levelData = $nbt->getData();
		if($levelData instanceof Compound){
			$this->levelData = $levelData;
		}else{
			throw new LevelException("Invalid level.dat");
		}

		if(!isset($this->levelData->generatorName)){
			$this->levelData->generatorName = new String("generatorName", Generator::getGenerator("DEFAULT"));
		}

		if(!isset($this->levelData->generatorOptions)){
			$this->levelData->generatorOptions = new String("generatorOptions", "");
		}

		$this->db = new \LevelDB($this->path . "/db", [
			"compression" => LEVELDB_ZLIB_COMPRESSION
		]);
	}

	public static function getProviderName(){
		return "leveldb";
	}

	public static function getProviderOrder(){
		return self::ORDER_ZXY;
	}

	public static function usesChunkSection(){
		return false;
	}

	public static function isValid($path){
		return file_exists($path . "/level.dat") and is_dir($path . "/db/");
	}

	public static function generate($path, $name, $seed, $generator, array $options = []){
		if(!file_exists($path)){
			mkdir($path, 0777, true);
		}
		if(!file_exists($path . "/db")){
			mkdir($path . "/db", 0777, true);
		}
		//TODO, add extra details
		$levelData = new Compound("", [
			"hardcore" => new Byte("hardcore", 0),
			"initialized" => new Byte("initialized", 1),
			"GameType" => new Int("GameType", 0),
			"generatorVersion" => new Int("generatorVersion", 1), //2 in MCPE
			"SpawnX" => new Int("SpawnX", 128),
			"SpawnY" => new Int("SpawnY", 70),
			"SpawnZ" => new Int("SpawnZ", 128),
			"version" => new Int("version", 19133),
			"DayTime" => new Int("DayTime", 0),
			"LastPlayed" => new Long("LastPlayed", microtime(true) * 1000),
			"RandomSeed" => new Long("RandomSeed", $seed),
			"SizeOnDisk" => new Long("SizeOnDisk", 0),
			"Time" => new Long("Time", 0),
			"generatorName" => new String("generatorName", Generator::getGeneratorName($generator)),
			"generatorOptions" => new String("generatorOptions", isset($options["preset"]) ? $options["preset"] : ""),
			"LevelName" => new String("LevelName", $name),
			"GameRules" => new Compound("GameRules", [])
		]);
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		$nbt->setData($levelData);
		$buffer = $nbt->write();
		file_put_contents($path . "level.dat", Binary::writeLInt(3) . Binary::writeLInt(strlen($buffer)) . $buffer);

		$db = new \LevelDB($path . "/db");
		$db->close();
	}

	public function saveLevelData(){
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		$nbt->setData($this->levelData);
		$buffer = $nbt->write();
		file_put_contents($this->getPath() . "level.dat", Binary::writeLInt(3) . Binary::writeLInt(strlen($buffer)) . $buffer);
	}

	public function requestChunkTask($x, $z){
		$chunk = $this->getChunk($x, $z, false);
		if(!($chunk instanceof Chunk)){
			throw new ChunkException("Invalid Chunk sent");
		}

		$tiles = "";
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		foreach($chunk->getTiles() as $tile){
			if($tile instanceof Spawnable){
				$nbt->setData($tile->getSpawnCompound());
				$tiles .= $nbt->write();
			}
		}

		$heightmap = pack("C*", ...$chunk->getHeightMapArray());
		$biomeColors = pack("N*", ...$chunk->getBiomeColorArray());

		$ordered = $chunk->getBlockIdArray() .
			$chunk->getBlockDataArray() .
			$chunk->getBlockSkyLightArray() .
			$chunk->getBlockLightArray() .
			$heightmap .
			$biomeColors .
			$tiles;

		$this->getLevel()->chunkRequestCallback($x, $z, $ordered);

		return null;
	}

	public function unloadChunks(){
		foreach($this->chunks as $chunk){
			$this->unloadChunk($chunk->getX(), $chunk->getZ(), false);
		}
		$this->chunks = [];
	}

	public function getGenerator(){
		return $this->levelData["generatorName"];
	}

	public function getGeneratorOptions(){
		return ["preset" => $this->levelData["generatorOptions"]];
	}

	public function getLoadedChunks(){
		return $this->chunks;
	}

	public function isChunkLoaded($x, $z){
		return isset($this->chunks[Level::chunkHash($x, $z)]);
	}

	public function saveChunks(){
		foreach($this->chunks as $chunk){
			$this->saveChunk($chunk->getX(), $chunk->getZ());
		}
	}

	public function loadChunk($chunkX, $chunkZ, $create = false){
		if(isset($this->chunks[$index = Level::chunkHash($chunkX, $chunkZ)])){
			return true;
		}

		$this->level->timings->syncChunkLoadDataTimer->startTiming();
		$chunk = $this->readChunk($chunkX, $chunkZ, $create);
		if($chunk === null and $create){
			$chunk = Chunk::getEmptyChunk($chunkX, $chunkZ, $this);
		}
		$this->level->timings->syncChunkLoadDataTimer->stopTiming();

		if($chunk !== null){
			$this->chunks[$index] = $chunk;
			return true;
		}else{
			return false;
		}
	}

	/**
	 * @param      $chunkX
	 * @param      $chunkZ
	 *
	 * @return Chunk
	 */
	private function readChunk($chunkX, $chunkZ){
		$index = LevelDB::chunkIndex($chunkX, $chunkZ);

		if(!$this->chunkExists($chunkX, $chunkZ) or ($data = $this->db->get($index . "\x30")) === false){
			return null;
		}

		$flags = $this->db->get($index . "f");
		if($flags === false){
			$flags = "\x03";
		}

		return Chunk::fromBinary($index . $data . $flags, $this);
	}

	private function generateChunk($chunkX, $chunkZ){
		return new Chunk($this, $chunkX, $chunkZ, str_repeat("\x00", 32768) .
			str_repeat("\x00", 16384) . str_repeat("\xff", 16384) . str_repeat("\x00", 16384) .
			str_repeat("\x01", 256) .
			str_repeat("\x00\x85\xb2\x4a", 256));
	}

	private function writeChunk(Chunk $chunk){
		$binary = $chunk->toBinary(true);
		$index = LevelDB::chunkIndex($chunk->getX(), $chunk->getZ());
		$this->db->put($index . "\x30", substr($binary, 8, -1));
		$this->db->put($index . "f", substr($binary, -1));
		$this->db->put($index . "v", "\x02");
	}

	public function unloadChunk($x, $z, $safe = true){
		$chunk = isset($this->chunks[$index = Level::chunkHash($x, $z)]) ? $this->chunks[$index] : null;
		if($chunk instanceof FullChunk and $chunk->unload(false, $safe)){
			unset($this->chunks[$index]);
			return true;
		}

		return false;
	}

	public function saveChunk($x, $z){
		if($this->isChunkLoaded($x, $z)){
			$this->writeChunk($this->getChunk($x, $z));

			return true;
		}

		return false;
	}

	/**
	 * @param int  $chunkX
	 * @param int  $chunkZ
	 * @param bool $create
	 *
	 * @return Chunk
	 */
	public function getChunk($chunkX, $chunkZ, $create = false){
		$index = Level::chunkHash($chunkX, $chunkZ);
		if(isset($this->chunks[$index])){
			return $this->chunks[$index];
		}else{
			$this->loadChunk($chunkX, $chunkZ, $create);

			return isset($this->chunks[$index]) ? $this->chunks[$index] : null;
		}
	}

	/**
	 * @return \LevelDB
	 */
	public function getDatabase(){
		return $this->db;
	}

	public function setChunk($chunkX, $chunkZ, FullChunk $chunk){
		if(!($chunk instanceof Chunk)){
			throw new ChunkException("Invalid Chunk class");
		}

		$chunk->setProvider($this);

		$chunk->setX($chunkX);
		$chunk->setZ($chunkZ);

		if(isset($this->chunks[$index = Level::chunkHash($chunkX, $chunkZ)]) and $this->chunks[$index] !== $chunk){
			$this->unloadChunk($chunkX, $chunkZ, false);
		}

		$this->chunks[$index] = $chunk;
	}

	public static function createChunkSection($Y){
		return null;
	}

	public static function chunkIndex($chunkX, $chunkZ){
		return Binary::writeLInt($chunkX) . Binary::writeLInt($chunkZ);
	}

	private function chunkExists($chunkX, $chunkZ){
		return $this->db->get(LevelDB::chunkIndex($chunkX, $chunkZ) . "v") !== false;
	}

	public function isChunkGenerated($chunkX, $chunkZ){
		if($this->chunkExists($chunkX, $chunkZ) and ($chunk = $this->getChunk($chunkX, $chunkZ, false)) !== null){
			return true;
		}

		return false;
	}

	public function isChunkPopulated($chunkX, $chunkZ){
		$chunk = $this->getChunk($chunkX, $chunkZ);
		if($chunk instanceof FullChunk){
			return $chunk->isPopulated();
		}else{
			return false;
		}
	}

	public function close(){
		$this->unloadChunks();
		$this->db->close();
		$this->level = null;
	}
}