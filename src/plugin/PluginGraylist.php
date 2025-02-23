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

use pocketmine\utils\Utils;
use function array_flip;
use function is_array;
use function is_float;
use function is_int;
use function is_string;

class PluginGraylist{

	/** @var string[] */
	private array $plugins;
	private bool $isWhitelist = false;

	/**
	 * @param string[] $plugins
	 */
	public function __construct(array $plugins = [], bool $whitelist = false){
		$this->plugins = array_flip($plugins);
		$this->isWhitelist = $whitelist;
	}

	/**
	 * @return string[]
	 */
	public function getPlugins() : array{
		return array_flip($this->plugins);
	}

	public function isWhitelist() : bool{
		return $this->isWhitelist;
	}

	/**
	 * Returns whether the given name is permitted by this graylist.
	 */
	public function isAllowed(string $name) : bool{
		return $this->isWhitelist() === isset($this->plugins[$name]);
	}

	/**
	 * @param mixed[] $array
	 */
	public static function fromArray(array $array) : PluginGraylist{
		if(!isset($array["mode"]) || ($array["mode"] !== "whitelist" && $array["mode"] !== "blacklist")){
			throw new \InvalidArgumentException("\"mode\" must be set");
		}
		$isWhitelist = match($array["mode"]){
			"whitelist" => true,
			"blacklist" => false
		};
		$plugins = [];
		if(isset($array["plugins"])){
			if(!is_array($array["plugins"])){
				throw new \InvalidArgumentException("\"plugins\" must be an array");
			}
			foreach(Utils::promoteKeys($array["plugins"]) as $k => $v){
				if(!is_string($v) && !is_int($v) && !is_float($v)){
					throw new \InvalidArgumentException("\"plugins\" contains invalid element at position $k");
				}
				$plugins[] = (string) $v;
			}
		}
		return new PluginGraylist($plugins, $isWhitelist);
	}

	/**
	 * @return mixed[]
	 * @phpstan-return array<string, mixed>
	 */
	public function toArray() : array{
		return [
			"mode" => $this->isWhitelist ? 'whitelist' : 'blacklist',
			"plugins" => $this->plugins
		];
	}
}
