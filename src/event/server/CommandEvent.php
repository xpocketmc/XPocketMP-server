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

namespace pocketmine\event\server;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

/**
 * Called when any CommandSender runs a command, before it is parsed.
 *
 * This can be used for logging commands, or preprocessing the command string to add custom features (e.g. selectors).
 *
 * WARNING: DO NOT use this to block commands. Many commands have aliases.
 * For example, /version can also be invoked using /ver or /about.
 * To prevent command senders from using certain commands, deny them permission to use the commands you don't want them
 * to have access to.
 *
 * @see Permissible::addAttachment()
 *
 * The message DOES NOT begin with a slash.
 */
class CommandEvent extends ServerEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		protected CommandSender $sender,
		protected string $command
	){}

	public function getSender() : CommandSender{
		return $this->sender;
	}

	public function getCommand() : string{
		return $this->command;
	}

	public function setCommand(string $command) : void{
		$this->command = $command;
	}
}
