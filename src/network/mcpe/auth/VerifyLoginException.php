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

namespace pocketmine\network\mcpe\auth;

use pocketmine\lang\Translatable;

class VerifyLoginException extends \RuntimeException{

	private Translatable|string $disconnectMessage;

	public function __construct(string $message, Translatable|string|null $disconnectMessage = null, int $code = 0, ?\Throwable $previous = null){
		parent::__construct($message, $code, $previous);
		$this->disconnectMessage = $disconnectMessage ?? $message;
	}

	public function getDisconnectMessage() : Translatable|string{ return $this->disconnectMessage; }
}
