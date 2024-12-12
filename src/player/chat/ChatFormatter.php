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

namespace pocketmine\player\chat;

use pocketmine\lang\Translatable;

/**
 * Formats chat messages for broadcasting. Used in PlayerChatEvent.
 */
interface ChatFormatter{
	/**
	 * Returns the formatted message to broadcast.
	 * This can return a plain string (which will be used as-is) or a Translatable (which will be translated into
	 * each recipient's language).
	 */
	public function format(string $username, string $message) : Translatable|string;
}
