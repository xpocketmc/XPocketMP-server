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

namespace pocketmine\utils;

use pocketmine\command\CommandSender;
use pocketmine\lang\Language;
use pocketmine\lang\Translatable;
use pocketmine\permission\PermissibleBase;
use pocketmine\permission\PermissibleDelegateTrait;
use pocketmine\Server;
use const PHP_INT_MAX;

/**
 * Forwards any messages it receives via sendMessage() to the given logger. Used for forwarding chat messages and
 * command audit log messages to the server log file.
 *
 * Unfortunately, broadcast subscribers are currently required to implement CommandSender, so this class has to include
 * a lot of useless methods.
 */
final class BroadcastLoggerForwarder implements CommandSender{
	use PermissibleDelegateTrait;

	public function __construct(
		private Server $server, //annoying useless dependency
		private \Logger $logger,
		private Language $language
	){
		//this doesn't need any permissions
		$this->perm = new PermissibleBase([]);
	}

	public function getLanguage() : Language{
		return $this->language;
	}

	public function sendMessage(Translatable|string $message) : void{
		if($message instanceof Translatable){
			$this->logger->info($this->language->translate($message));
		}else{
			$this->logger->info($message);
		}
	}

	public function getServer() : Server{
		return $this->server;
	}

	public function getName() : string{
		return "Broadcast Logger Forwarder";
	}

	public function getScreenLineHeight() : int{
		return PHP_INT_MAX;
	}

	public function setScreenLineHeight(?int $height) : void{
		//NOOP
	}
}
