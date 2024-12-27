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
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\player\Player;
use function array_filter;
use function array_map;
use function count;
use function implode;
use function sort;
use const SORT_STRING;

class ListCommand extends VanillaCommand{

	public function __construct(){
		parent::__construct(
			"list",
			KnownTranslationFactory::pocketmine_command_list_description()
		);
		$this->setPermission(DefaultPermissionNames::COMMAND_LIST);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		$playerNames = array_map(function(Player $player) : string{
			return $player->getName();
		}, array_filter($sender->getServer()->getOnlinePlayers(), function(Player $player) use ($sender) : bool{
			return !($sender instanceof Player) || $sender->canSee($player);
		}));
		sort($playerNames, SORT_STRING);

		$sender->sendMessage(KnownTranslationFactory::commands_players_list((string) count($playerNames), (string) $sender->getServer()->getMaxPlayers()));
		$sender->sendMessage(implode(", ", $playerNames));

		return true;
	}
}
