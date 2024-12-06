<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author XPocketMP Team
 * @link http://www.xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace XPocketMP\command\defaults;

use XPocketMP\command\Command;
use XPocketMP\command\CommandSender;
use XPocketMP\command\utils\InvalidCommandSyntaxException;
use XPocketMP\event\entity\EntityDamageEvent;
use XPocketMP\lang\KnownTranslationFactory;
use XPocketMP\permission\DefaultPermissionNames;
use function count;

class KillCommand extends VanillaCommand{

	public function __construct(){
		parent::__construct(
			"kill",
			KnownTranslationFactory::XPocketMP_command_kill_description(),
			KnownTranslationFactory::XPocketMP_command_kill_usage(),
			["suicide"]
		);
		$this->setPermissions([DefaultPermissionNames::COMMAND_KILL_SELF, DefaultPermissionNames::COMMAND_KILL_OTHER]);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(count($args) >= 2){
			throw new InvalidCommandSyntaxException();
		}

		$player = $this->fetchPermittedPlayerTarget($sender, $args[0] ?? null, DefaultPermissionNames::COMMAND_KILL_SELF, DefaultPermissionNames::COMMAND_KILL_OTHER);
		if($player === null){
			return true;
		}

		$player->attack(new EntityDamageEvent($player, EntityDamageEvent::CAUSE_SUICIDE, $player->getHealth()));
		if($player === $sender){
			$sender->sendMessage(KnownTranslationFactory::commands_kill_successful($sender->getName()));
		}else{
			Command::broadcastCommandMessage($sender, KnownTranslationFactory::commands_kill_successful($player->getName()));
		}

		return true;
	}
}