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

namespace pocketmine\scheduler;

use pmmp\thread\Thread as NativeThread;
use pocketmine\MemoryManager;
use Symfony\Component\Filesystem\Path;
use function assert;

/**
 * Task used to dump memory from AsyncWorkers
 */
class DumpWorkerMemoryTask extends AsyncTask{
	public function __construct(
		private string $outputFolder,
		private int $maxNesting,
		private int $maxStringSize
	){}

	public function onRun() : void{
		$worker = NativeThread::getCurrentThread();
		assert($worker instanceof AsyncWorker);
		MemoryManager::dumpMemory(
			$worker,
			Path::join($this->outputFolder, "AsyncWorker#" . $worker->getAsyncWorkerId()),
			$this->maxNesting,
			$this->maxStringSize,
			new \PrefixedLogger($worker->getLogger(), "Memory Dump")
		);
	}
}
