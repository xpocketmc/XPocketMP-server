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

use XPocketMP\command\CommandSender;
use XPocketMP\lang\KnownTranslationFactory;
use XPocketMP\permission\DefaultPermissionNames;
use XPocketMP\player\Player;

class SeedCommand extends VanillaCommand{

	public function __construct(){
		parent::__construct(
			"seed",
			KnownTranslationFactory::XPocketMP_command_seed_description()
		);
		$this->setPermission(DefaultPermissionNames::COMMAND_SEED);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if($sender instanceof Player){
			$seed = $sender->getPosition()->getWorld()->getSeed();
		}else{
			$seed = $sender->getServer()->getWorldManager()->getDefaultWorld()->getSeed();
		}
		$sender->sendMessage(KnownTranslationFactory::commands_seed_success((string) $seed));

		return true;
	}
}