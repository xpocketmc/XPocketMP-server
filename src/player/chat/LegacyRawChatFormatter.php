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
 * Developed: ClousClouds, PMMP Team
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\player\chat;

use function str_replace;

/**
 * Legacy raw string chat formatter with the same behaviour as the old PlayerChatEvent::setFormat() API.
 * The format string should contain the placeholders {%0} and {%1} for the username and message respectively.
 */
final class LegacyRawChatFormatter implements ChatFormatter{

	public function __construct(
		private string $format
	){}

	public function format(string $username, string $message) : string{
		return str_replace(["{%0}", "{%1}"], [$username, $message], $this->format);
	}
}
