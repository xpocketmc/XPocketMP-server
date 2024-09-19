<?php

namespace pocketmine\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class EnableCommand extends Command {

    private string $dCommand;
    private string $dataFile;

    public function __construct(string $dCommand, string $dataFile = __DIR__ . "/../../data/disablecommanddata.json") {
        parent::__construct("enablecommand", "Re-enable command", "/enablecommand <commandname>");
        $this->setPermission("pocketmine.command.enablecommand");
        $this->dCommand = $dCommand;
        $this->dataFile = $dataFile;
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage("You do not have permission to use this command.");
            return false;
        }

        if (count($args) < 1) {
            $sender->sendMessage("Usage: /enablecommand <commandname>");
            return false;
        }

        $commandToEnable = strtolower(array_shift($args));
        $disableCmd = new DisableCommand($commandToEnable, $this->dataFile);

        $disableCmd->enable();
        $sender->sendMessage("Command '" . $commandToEnable . "' has been reactivated.");
        return true;
    }
}
