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

namespace XPocketMP\command;

use XPocketMP\command\defaults\BanCommand;
use XPocketMP\command\defaults\BanIpCommand;
use XPocketMP\command\defaults\BanListCommand;
use XPocketMP\command\defaults\ClearCommand;
use XPocketMP\command\defaults\DefaultGamemodeCommand;
use XPocketMP\command\defaults\DeopCommand;
use XPocketMP\command\defaults\DifficultyCommand;
use XPocketMP\command\defaults\DumpMemoryCommand;
use XPocketMP\command\defaults\EffectCommand;
use XPocketMP\command\defaults\EnchantCommand;
use XPocketMP\command\defaults\GamemodeCommand;
use XPocketMP\command\defaults\GarbageCollectorCommand;
use XPocketMP\command\defaults\GiveCommand;
use XPocketMP\command\defaults\HelpCommand;
use XPocketMP\command\defaults\KickCommand;
use XPocketMP\command\defaults\KillCommand;
use XPocketMP\command\defaults\ListCommand;
use XPocketMP\command\defaults\MeCommand;
use XPocketMP\command\defaults\OpCommand;
use XPocketMP\command\defaults\PardonCommand;
use XPocketMP\command\defaults\PardonIpCommand;
use XPocketMP\command\defaults\ParticleCommand;
use XPocketMP\command\defaults\PluginsCommand;
use XPocketMP\command\defaults\SaveCommand;
use XPocketMP\command\defaults\SaveOffCommand;
use XPocketMP\command\defaults\SaveOnCommand;
use XPocketMP\command\defaults\SayCommand;
use XPocketMP\command\defaults\SeedCommand;
use XPocketMP\command\defaults\SetWorldSpawnCommand;
use XPocketMP\command\defaults\SpawnpointCommand;
use XPocketMP\command\defaults\StatusCommand;
use XPocketMP\command\defaults\StopCommand;
use XPocketMP\command\defaults\TeleportCommand;
use XPocketMP\command\defaults\TellCommand;
use XPocketMP\command\defaults\TimeCommand;
use XPocketMP\command\defaults\TimingsCommand;
use XPocketMP\command\defaults\TitleCommand;
use XPocketMP\command\defaults\TransferServerCommand;
use XPocketMP\command\defaults\VanillaCommand;
use XPocketMP\command\defaults\VersionCommand;
use XPocketMP\command\defaults\WhitelistCommand;
use XPocketMP\command\utils\CommandStringHelper;
use XPocketMP\command\utils\InvalidCommandSyntaxException;
use XPocketMP\lang\KnownTranslationFactory;
use XPocketMP\Server;
use XPocketMP\timings\Timings;
use XPocketMP\utils\TextFormat;
use XPocketMP\utils\Utils;
use function array_shift;
use function count;
use function implode;
use function str_contains;
use function strcasecmp;
use function strtolower;
use function trim;

class SimpleCommandMap implements CommandMap{

	/**
	 * @var Command[]
	 * @phpstan-var array<string, Command>
	 */
	protected array $knownCommands = [];

	public function __construct(private Server $server){
		$this->setDefaultCommands();
	}

	private function setDefaultCommands() : void{
		$this->registerAll("XPocketMP", [
			new BanCommand(),
			new BanIpCommand(),
			new BanListCommand(),
			new ClearCommand(),
			new DefaultGamemodeCommand(),
			new DeopCommand(),
			new DifficultyCommand(),
			new DumpMemoryCommand(),
			new EffectCommand(),
			new EnchantCommand(),
			new GamemodeCommand(),
			new GarbageCollectorCommand(),
			new GiveCommand(),
			new HelpCommand(),
			new KickCommand(),
			new KillCommand(),
			new ListCommand(),
			new MeCommand(),
			new OpCommand(),
			new PardonCommand(),
			new PardonIpCommand(),
			new ParticleCommand(),
			new PluginsCommand(),
			new SaveCommand(),
			new SaveOffCommand(),
			new SaveOnCommand(),
			new SayCommand(),
			new SeedCommand(),
			new SetWorldSpawnCommand(),
			new SpawnpointCommand(),
			new StatusCommand(),
			new StopCommand(),
			new TeleportCommand(),
			new TellCommand(),
			new TimeCommand(),
			new TimingsCommand(),
			new TitleCommand(),
			new TransferServerCommand(),
			new VersionCommand(),
			new WhitelistCommand()
		]);
	}

	public function registerAll(string $fallbackPrefix, array $commands) : void{
		foreach($commands as $command){
			$this->register($fallbackPrefix, $command);
		}
	}

	public function register(string $fallbackPrefix, Command $command, ?string $label = null) : bool{
		if(count($command->getPermissions()) === 0){
			throw new \InvalidArgumentException("Commands must have a permission set");
		}

		if($label === null){
			$label = $command->getLabel();
		}
		$label = trim($label);
		$fallbackPrefix = strtolower(trim($fallbackPrefix));

		$registered = $this->registerAlias($command, false, $fallbackPrefix, $label);

		$aliases = $command->getAliases();
		foreach($aliases as $index => $alias){
			if(!$this->registerAlias($command, true, $fallbackPrefix, $alias)){
				unset($aliases[$index]);
			}
		}
		$command->setAliases($aliases);

		if(!$registered){
			$command->setLabel($fallbackPrefix . ":" . $label);
		}

		$command->register($this);

		return $registered;
	}

	public function unregister(Command $command) : bool{
		foreach(Utils::promoteKeys($this->knownCommands) as $lbl => $cmd){
			if($cmd === $command){
				unset($this->knownCommands[$lbl]);
			}
		}

		$command->unregister($this);

		return true;
	}

	private function registerAlias(Command $command, bool $isAlias, string $fallbackPrefix, string $label) : bool{
		$this->knownCommands[$fallbackPrefix . ":" . $label] = $command;
		if(($command instanceof VanillaCommand || $isAlias) && isset($this->knownCommands[$label])){
			return false;
		}

		if(isset($this->knownCommands[$label]) && $this->knownCommands[$label]->getLabel() === $label){
			return false;
		}

		if(!$isAlias){
			$command->setLabel($label);
		}

		$this->knownCommands[$label] = $command;

		return true;
	}

	public function dispatch(CommandSender $sender, string $commandLine) : bool{
		$args = CommandStringHelper::parseQuoteAware($commandLine);

		$sentCommandLabel = array_shift($args);
		if($sentCommandLabel !== null && ($target = $this->getCommand($sentCommandLabel)) !== null){
			$timings = Timings::getCommandDispatchTimings($target->getLabel());
			$timings->startTiming();

			try{
				if($target->testPermission($sender)){
					$target->execute($sender, $sentCommandLabel, $args);
				}
			}catch(InvalidCommandSyntaxException $e){
				$sender->sendMessage($sender->getLanguage()->translate(KnownTranslationFactory::commands_generic_usage($target->getUsage())));
			}finally{
				$timings->stopTiming();
			}
			return true;
		}

		$sender->sendMessage(KnownTranslationFactory::XPocketMP_command_notFound($sentCommandLabel ?? "", "/help")->prefix(TextFormat::RED));
		return false;
	}

	public function clearCommands() : void{
		foreach($this->knownCommands as $command){
			$command->unregister($this);
		}
		$this->knownCommands = [];
		$this->setDefaultCommands();
	}

	public function getCommand(string $name) : ?Command{
		return $this->knownCommands[$name] ?? null;
	}

	/**
	 * @return Command[]
	 * @phpstan-return array<string, Command>
	 */
	public function getCommands() : array{
		return $this->knownCommands;
	}

	public function registerServerAliases() : void{
		/**
 		 * @var array<string, array<string>> $values
		 */
		$values = $this->server->getCommandAliases();

		foreach(Utils::stringifyKeys($values) as $alias => $commandStrings){
			if(str_contains($alias, ":")){
				$this->server->getLogger()->warning($this->server->getLanguage()->translate(KnownTranslationFactory::XPocketMP_command_alias_illegal($alias)));
				continue;
			}

			$targets = [];
			$bad = [];
			$recursive = [];

			foreach($commandStrings as $commandString){
				$args = CommandStringHelper::parseQuoteAware($commandString);
				$commandName = array_shift($args) ?? "";
				$command = $this->getCommand($commandName);

				if($command === null){
					$bad[] = $commandString;
				}elseif(strcasecmp($commandName, $alias) === 0){
					$recursive[] = $commandString;
				}else{
					$targets[] = $commandString;
				}
			}

			if(count($recursive) > 0){
				$this->server->getLogger()->warning($this->server->getLanguage()->translate(KnownTranslationFactory::XPocketMP_command_alias_recursive($alias, implode(", ", $recursive))));
				continue;
			}

			if(count($bad) > 0){
				$this->server->getLogger()->warning($this->server->getLanguage()->translate(KnownTranslationFactory::XPocketMP_command_alias_notFound($alias, implode(", ", $bad))));
				continue;
			}

			//These registered commands have absolute priority
			$lowerAlias = strtolower($alias);
			if(count($targets) > 0){
				$this->knownCommands[$lowerAlias] = new FormattedCommandAlias($lowerAlias, $targets);
			}else{
				unset($this->knownCommands[$lowerAlias]);
			}

		}
	}
}