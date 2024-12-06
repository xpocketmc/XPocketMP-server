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
use XPocketMP\lang\KnownTranslationFactory;
use XPocketMP\permission\DefaultPermissionNames;
use function microtime;
use function round;

class SaveCommand extends VanillaCommand{

	public function __construct(){
		parent::__construct(
			"save-all",
			KnownTranslationFactory::XPocketMP_command_save_description()
		);
		$this->setPermission(DefaultPermissionNames::COMMAND_SAVE_PERFORM);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		Command::broadcastCommandMessage($sender, KnownTranslationFactory::XPocketMP_save_start());
		$start = microtime(true);

		foreach($sender->getServer()->getOnlinePlayers() as $player){
			$player->save();
		}

		foreach($sender->getServer()->getWorldManager()->getWorlds() as $world){
			$world->save(true);
		}

		Command::broadcastCommandMessage($sender, KnownTranslationFactory::XPocketMP_save_success((string) round(microtime(true) - $start, 3)));

		return true;
	}
}