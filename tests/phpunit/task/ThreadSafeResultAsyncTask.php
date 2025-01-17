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

namespace pocketmine\task;

use pmmp\thread\ThreadSafeArray;
use pocketmine\async\PromiseResolver;

class ThreadSafeResultAsyncTask extends AsyncTask{
	private const TLS_KEY_PROMISE = "promise";

	/**
	 * @phpstan-param PromiseResolver<ThreadSafeArray<array-key, mixed>> $promise
	 */
	public function __construct(
		PromiseResolver $promise
	){
		$this->storeLocal(self::TLS_KEY_PROMISE, $promise);
	}

	public function onRun() : void{
		//this only works in pthreads 5.1+ and pmmpthread
		//in prior versions the ThreadSafe would be destroyed before onCompletion is called
		$result = new ThreadSafeArray();
		$result[] = "foo";
		$this->setResult($result);
	}

	public function onCompletion() : void{
		/** @var PromiseResolver<ThreadSafeArray<array-key, mixed>> $promise */
		$promise = $this->fetchLocal(self::TLS_KEY_PROMISE);
		/** @var ThreadSafeArray<array-key, mixed> $result */
		$result = $this->getResult();
		$promise->resolve($result);
	}
}
