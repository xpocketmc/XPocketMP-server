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

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\permission\DefaultPermissionNames;
use Symfony\Component\Filesystem\Path;
use function date;

class DumpMemoryCommand extends VanillaCommand{

	public function __construct(){
		parent::__construct(
			"dumpmemory",
			KnownTranslationFactory::pocketmine_command_dumpmemory_description(),
			"/dumpmemory [path]"
		);
		$this->setPermission(DefaultPermissionNames::COMMAND_DUMPMEMORY);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		$sender->getServer()->getMemoryManager()->dumpServerMemory($args[0] ?? (Path::join($sender->getServer()->getDataPath(), "memory_dumps", date("D_M_j-H.i.s-T_Y"))), 48, 80);
		return true;
	}
}
