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

namespace pocketmine\plugin;

use pocketmine\Server;
use pocketmine\thread\ThreadSafeClassLoader;
use function is_file;
use function str_ends_with;

/**
 * Handles different types of plugins
 */
class PharPluginLoader implements PluginLoader {
	private Server $server;

	public function __construct(
		private ThreadSafeClassLoader $loader
	) {
		$this->server = Server::getInstance();
	}

	/**
	 * Determines if the given path can be loaded as a plugin
	 *
	 * @param string $path
	 * @return bool
	 */
	public function canLoadPlugin(string $path): bool {
		return is_file($path) && str_ends_with($path, ".phar");
	}

	/**
	 * Loads the plugin contained in $file
	 *
	 * @param string $file
	 * @return void
	 */
	public function loadPlugin(string $file): void {
		$description = $this->getPluginDescription($file);
		if ($description !== null) {
			$this->loader->addPath($description->getSrcNamespacePrefix(), "$file/src");

			try {
				$loadedPlugins = $this->getServer()->getPluginManager()->loadPlugins($file);
				$pluginInstance = null;

				if ($loadedPlugins instanceof Plugin) {
					$pluginInstance = $loadedPlugins;
					$pluginInstance->onLoad();
				} elseif (is_array($loadedPlugins)) {
					foreach ($loadedPlugins as $plugin) {
						if ($plugin instanceof Plugin) {
							$pluginInstance = $plugin;
							$plugin->onLoad();
							break; // Only handle the first valid plugin
						}
					}
				}

				if ($pluginInstance === null) {
					throw new \RuntimeException("Failed to load plugin from $file");
				}
			} catch (\Throwable $e) {
				CrashHandler::getInstance()->handlePluginCrash($pluginInstance, $e);
			}
		}
	}

	/**
	 * Gets the PluginDescription from the file
	 *
	 * @param string $file
	 * @return PluginDescription|null
	 */
	public function getPluginDescription(string $file): ?PluginDescription {
		$phar = new \Phar($file);
		if (isset($phar["plugin.yml"])) {
			return new PluginDescription($phar["plugin.yml"]->getContent());
		}
		return null;
	}

	/**
	 * Returns the access protocol
	 *
	 * @return string
	 */
	public function getAccessProtocol(): string {
		return "phar://";
	}

	/**
	 * Gets the server instance
	 *
	 * @return Server
	 */
	public function getServer(): Server {
		return $this->server;
	}
}
