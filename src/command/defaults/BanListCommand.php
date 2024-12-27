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

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\permission\BanEntry;
use pocketmine\permission\DefaultPermissionNames;
use function array_map;
use function count;
use function implode;
use function sort;
use function strtolower;
use const SORT_STRING;

class BanListCommand extends VanillaCommand{

	public function __construct(){
		parent::__construct(
			"banlist",
			KnownTranslationFactory::pocketmine_command_banlist_description(),
			KnownTranslationFactory::commands_banlist_usage()
		);
		$this->setPermission(DefaultPermissionNames::COMMAND_BAN_LIST);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(isset($args[0])){
			$args[0] = strtolower($args[0]);
			if($args[0] === "ips"){
				$list = $sender->getServer()->getIPBans();
			}elseif($args[0] === "players"){
				$list = $sender->getServer()->getNameBans();
			}else{
				throw new InvalidCommandSyntaxException();
			}
		}else{
			$list = $sender->getServer()->getNameBans();
			$args[0] = "players";
		}

		$list = array_map(function(BanEntry $entry) : string{
			return $entry->getName();
		}, $list->getEntries());
		sort($list, SORT_STRING);
		$message = implode(", ", $list);

		if($args[0] === "ips"){
			$sender->sendMessage(KnownTranslationFactory::commands_banlist_ips((string) count($list)));
		}else{
			$sender->sendMessage(KnownTranslationFactory::commands_banlist_players((string) count($list)));
		}

		$sender->sendMessage($message);

		return true;
	}
}
