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
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\permission\DefaultPermissionNames;

class SaveOffCommand extends VanillaCommand{

	public function __construct(){
		parent::__construct(
			"save-off",
			KnownTranslationFactory::pocketmine_command_saveoff_description()
		);
		$this->setPermission(DefaultPermissionNames::COMMAND_SAVE_DISABLE);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		$sender->getServer()->getWorldManager()->setAutoSave(false);

		Command::broadcastCommandMessage($sender, KnownTranslationFactory::commands_save_disabled());

		return true;
	}
}
