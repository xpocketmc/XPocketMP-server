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
use XPocketMP\lang\KnownTranslationFactory;
use XPocketMP\permission\DefaultPermissionNames;
use XPocketMP\player\Player;
use XPocketMP\utils\TextFormat;
use function array_shift;
use function count;
use function implode;
use function trim;

class KickCommand extends VanillaCommand{

	public function __construct(){
		parent::__construct(
			"kick",
			KnownTranslationFactory::XPocketMP_command_kick_description(),
			KnownTranslationFactory::commands_kick_usage()
		);
		$this->setPermission(DefaultPermissionNames::COMMAND_KICK);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(count($args) === 0){
			throw new InvalidCommandSyntaxException();
		}

		$name = array_shift($args);
		$reason = trim(implode(" ", $args));

		if(($player = $sender->getServer()->getPlayerByPrefix($name)) instanceof Player){
			$player->kick($reason !== "" ? KnownTranslationFactory::XPocketMP_disconnect_kick($reason) : KnownTranslationFactory::XPocketMP_disconnect_kick_noReason());
			if($reason !== ""){
				Command::broadcastCommandMessage($sender, KnownTranslationFactory::commands_kick_success_reason($player->getName(), $reason));
			}else{
				Command::broadcastCommandMessage($sender, KnownTranslationFactory::commands_kick_success($player->getName()));
			}
		}else{
			$sender->sendMessage(KnownTranslationFactory::commands_generic_player_notFound()->prefix(TextFormat::RED));
		}

		return true;
	}
}