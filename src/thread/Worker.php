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
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\thread;

use pmmp\thread\Worker as NativeWorker;
use pocketmine\scheduler\AsyncTask;

/**
 * Specialized Worker class for PocketMine-MP-related use cases. It handles setting up autoloading and error handling.
 *
 * Workers are a special type of thread which execute tasks passed to them during their lifetime. Since creating a new
 * thread has a high resource cost, workers can be kept around and reused for lots of short-lived tasks.
 *
 * As a plugin developer, you'll rarely (if ever) actually need to use this class directly.
 * If you want to run tasks on other CPU cores, check out AsyncTask first.
 * @see AsyncTask
 */
abstract class Worker extends NativeWorker{
	use CommonThreadPartsTrait;
}
