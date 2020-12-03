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

namespace pocketmine\world\generator;

use pocketmine\scheduler\AsyncTask;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\io\FastChunkSerializer;
use pocketmine\world\SimpleChunkManager;
use pocketmine\world\World;
use function intdiv;

class PopulationTask extends AsyncTask{
	private const TLS_KEY_WORLD = "world";

	/** @var bool */
	public $state;
	/** @var int */
	public $worldId;
	/** @var int */
	private $chunkX;
	/** @var int */
	private $chunkZ;

	/** @var string|null */
	public $chunk;

	/** @var string|null */
	public $chunk0;
	/** @var string|null */
	public $chunk1;
	/** @var string|null */
	public $chunk2;
	/** @var string|null */
	public $chunk3;

	//center chunk

	/** @var string|null */
	public $chunk5;
	/** @var string|null */
	public $chunk6;
	/** @var string|null */
	public $chunk7;
	/** @var string|null */
	public $chunk8;

	public function __construct(World $world, int $chunkX, int $chunkZ, ?Chunk $chunk){
		$this->state = true;
		$this->worldId = $world->getId();
		$this->chunkX = $chunkX;
		$this->chunkZ = $chunkZ;
		$this->chunk = $chunk !== null ? FastChunkSerializer::serializeWithoutLight($chunk) : null;

		foreach($world->getAdjacentChunks($chunkX, $chunkZ) as $i => $c){
			$this->{"chunk$i"} = $c !== null ? FastChunkSerializer::serializeWithoutLight($c) : null;
		}

		$this->storeLocal(self::TLS_KEY_WORLD, $world);
	}

	public function onRun() : void{
		$context = ThreadLocalGeneratorContext::fetch($this->worldId);
		if($context === null){
			$this->state = false;
			return;
		}
		$generator = $context->getGenerator();
		$manager = new SimpleChunkManager($context->getWorldHeight());

		/** @var Chunk[] $chunks */
		$chunks = [];

		$chunk = $this->chunk !== null ? FastChunkSerializer::deserialize($this->chunk) : new Chunk();

		for($i = 0; $i < 9; ++$i){
			if($i === 4){
				continue;
			}
			$ck = $this->{"chunk$i"};
			if($ck === null){
				$chunks[$i] = new Chunk();
			}else{
				$chunks[$i] = FastChunkSerializer::deserialize($ck);
			}
		}

		$manager->setChunk($this->chunkX, $this->chunkZ, $chunk);
		if(!$chunk->isGenerated()){
			$generator->generateChunk($manager, $this->chunkX, $this->chunkZ);
			$chunk = $manager->getChunk($this->chunkX, $this->chunkZ);
			$chunk->setGenerated();
		}

		foreach($chunks as $i => $c){
			$cX = (-1 + $i % 3) + $this->chunkX;
			$cZ = (-1 + intdiv($i, 3)) + $this->chunkZ;
			$manager->setChunk($cX, $cZ, $c);
			if(!$c->isGenerated()){
				$generator->generateChunk($manager, $cX, $cZ);
				$chunks[$i] = $manager->getChunk($cX, $cZ);
				$chunks[$i]->setGenerated();
			}
		}

		$generator->populateChunk($manager, $this->chunkX, $this->chunkZ);
		$chunk = $manager->getChunk($this->chunkX, $this->chunkZ);
		$chunk->setPopulated();

		$this->chunk = FastChunkSerializer::serializeWithoutLight($chunk);

		foreach($chunks as $i => $c){
			$this->{"chunk$i"} = $c->isDirty() ? FastChunkSerializer::serializeWithoutLight($c) : null;
		}
	}

	public function onCompletion() : void{
		/** @var World $world */
		$world = $this->fetchLocal(self::TLS_KEY_WORLD);
		if(!$world->isClosed()){
			if(!$this->state){
				$world->registerGeneratorToWorker($this->worker->getAsyncWorkerId());
			}

			$chunk = $this->chunk !== null ? FastChunkSerializer::deserialize($this->chunk) : null;

			for($i = 0; $i < 9; ++$i){
				if($i === 4){
					continue;
				}
				$c = $this->{"chunk$i"};
				if($c !== null){
					$xx = -1 + $i % 3;
					$zz = -1 + intdiv($i, 3);

					$c = FastChunkSerializer::deserialize($c);
					$world->generateChunkCallback($this->chunkX + $xx, $this->chunkZ + $zz, $this->state ? $c : null);
				}
			}

			$world->generateChunkCallback($this->chunkX, $this->chunkZ, $this->state ? $chunk : null);
		}
	}
}
