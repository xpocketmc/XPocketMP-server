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
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\utils;

use function str_repeat;
use function strlen;
use function trim;

final class Git{

	private function __construct(){
		//NOOP
	}

	/**
	 * Returns the git hash of the currently checked out head of the given repository, or null on failure.
	 *
	 * @param bool $dirty reference parameter, set to whether the repo has local changes
	 */
	public static function getRepositoryState(string $dir, bool &$dirty) : ?string{
		if(Process::execute("git -C \"$dir\" rev-parse HEAD", $out) === 0 && $out !== false && strlen($out = trim($out)) === 40){
			if(Process::execute("git -C \"$dir\" diff --quiet") === 1 || Process::execute("git -C \"$dir\" diff --cached --quiet") === 1){ //Locally-modified
				$dirty = true;
			}
			return $out;
		}
		return null;
	}

	/**
	 * Infallible, returns a string representing git state, or a string of zeros on failure.
	 * If the repo is dirty, a "-dirty" suffix is added.
	 */
	public static function getRepositoryStatePretty(string $dir) : string{
		$dirty = false;
		$detectedHash = self::getRepositoryState($dir, $dirty);
		if($detectedHash !== null){
			return $detectedHash . ($dirty ? "-dirty" : "");
		}
		return str_repeat("00", 20);
	}
}
