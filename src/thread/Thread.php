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

namespace pocketmine\thread;

use pmmp\thread\Thread as NativeThread;
use pocketmine	askAsyncTask;

/**
 * Specialized Thread class aimed at PocketMine-MP-related usages. It handles setting up autoloading and error handling.
 *
 * Note: You probably don't need a thread unless you're doing something in it that's expected to last a long time (or
 * indefinitely).
 * For CPU-demanding tasks that take a short amount of time, consider using AsyncTasks instead to make better use of the
 * CPU.
 * @see AsyncTask
 */
abstract class Thread extends NativeThread{
	use CommonThreadPartsTrait;
}
