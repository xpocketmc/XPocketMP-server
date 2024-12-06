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

class DeopCommand extends VanillaCommand{

	public function __construct(){
		parent::__construct(
			"deop",
			KnownTranslationFactory::XPocketMP_command_deop_description(),
			KnownTranslationFactory::commands_deop_usage()
		);
		$this->setPermission(DefaultPermissionNames::COMMAND_OP_TAKE);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(count($args) === 0){
			throw new InvalidCommandSyntaxException();
		}

		$name = array_shift($args);
		if(!Player::isValidUserName($name)){
			throw new InvalidCommandSyntaxException();
		}

		$sender->getServer()->removeOp($name);
		if(($player = $sender->getServer()->getPlayerExact($name)) !== null){
			$player->sendMessage(KnownTranslationFactory::commands_deop_message()->prefix(TextFormat::GRAY));
		}
		Command::broadcastCommandMessage($sender, KnownTranslationFactory::commands_deop_success($name));

		return true;
	}
}