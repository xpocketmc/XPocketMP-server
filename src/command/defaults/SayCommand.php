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
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use function count;
use function implode;

class SayCommand extends VanillaCommand{

	public function __construct(){
		parent::__construct(
			"say",
			KnownTranslationFactory::pocketmine_command_say_description(),
			KnownTranslationFactory::commands_say_usage()
		);
		$this->setPermission(DefaultPermissionNames::COMMAND_SAY);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(count($args) === 0){
			throw new InvalidCommandSyntaxException();
		}

		$sender->getServer()->broadcastMessage(KnownTranslationFactory::chat_type_announcement(
			$sender instanceof Player ? $sender->getDisplayName() : ($sender instanceof ConsoleCommandSender ? "Server" : $sender->getName()),
			implode(" ", $args)
		)->prefix(TextFormat::LIGHT_PURPLE));
		return true;
	}
}
