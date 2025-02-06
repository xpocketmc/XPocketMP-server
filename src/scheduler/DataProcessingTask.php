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

namespace pocketmine\scheduler;

use pocketmine\Server;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use function array_chunk;
use function array_map;
use function array_merge;
use function count;
use function max;
use function React\Promise\all;
use function React\Promise\resolve;
use function strtoupper;

class DataProcessingTask extends AsyncTask{

	/** @var array<string> */
	private array $data;

	/** @var int<1, max> */
	private int $batchSize;

	/**
	 * @param array<string> $data
	 */
	public function __construct(array $data, int $batchSize = 100){
		$this->data = $data;
		$this->batchSize = max(1, $batchSize);
	}

	/**
	 * @return PromiseInterface<array<string>>
	 */
	public function onRunAsync() : PromiseInterface{
		$deferred = new Deferred();

		$chunks = array_chunk($this->data, max(1, $this->batchSize));
		$promises = array_map(fn(array $chunk) : PromiseInterface => resolve($this->processBatch($chunk)), $chunks);

		all($promises)->then(
			function(array $results) use ($deferred) : void{
				$deferred->resolve(array_merge(...$results));
			},
			function(\Throwable $e) use ($deferred) : void{
				$deferred->reject($e);
			}
		);

		return $deferred->promise();
	}

	/**
	 * @param array<string> $batch
	 * @return array<string>
	 */
	private function processBatch(array $batch) : array{
		$processed = [];
		foreach ($batch as $item){
			$processed[] = strtoupper($item);
		}
		return $processed;
	}

	/**
	 * @param array<string> $result
	 */
	public function onComplete(array $result) : void{
		Server::getInstance()->getLogger()->info("Data processing complete. Processed items: " . count($result));
	}

	public function onRun() : void{
	}
}
