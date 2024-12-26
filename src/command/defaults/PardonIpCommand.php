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

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\permission\DefaultPermissionNames;
use function count;
use function inet_pton;

class PardonIpCommand extends VanillaCommand{

	public function __construct(){
		parent::__construct(
			"pardon-ip",
			KnownTranslationFactory::pocketmine_command_unban_ip_description(),
			KnownTranslationFactory::commands_unbanip_usage(),
			["unban-ip"]
		);
		$this->setPermission(DefaultPermissionNames::COMMAND_UNBAN_IP);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(count($args) !== 1){
			throw new InvalidCommandSyntaxException();
		}

		if(inet_pton($args[0]) !== false){
			$sender->getServer()->getIPBans()->remove($args[0]);
			$sender->getServer()->getNetwork()->unblockAddress($args[0]);
			Command::broadcastCommandMessage($sender, KnownTranslationFactory::commands_unbanip_success($args[0]));
		}else{
			$sender->sendMessage(KnownTranslationFactory::commands_unbanip_invalid());
		}

		return true;
	}
}
