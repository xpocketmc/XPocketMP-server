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
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\player;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\Server;

class OfflinePlayer implements IPlayer{

	/** @var string */
	private $name;
	/** @var Server */
	private $server;
	/** @var CompoundTag|null */
	private $namedtag = null;

	public function __construct(Server $server, string $name){
		$this->server = $server;
		$this->name = $name;
		$this->namedtag = $this->server->getOfflinePlayerData($this->name);
	}

	public function isOnline() : bool{
		return $this->getPlayer() !== null;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getPlayer() : ?Player{
		return $this->server->getPlayerExact($this->name);
	}

	public function getFirstPlayed() : ?int{
		return ($this->namedtag !== null and ($firstPlayedTag = $this->namedtag->getTag("firstPlayed")) instanceof LongTag) ? $firstPlayedTag->getValue() : null;
	}

	public function getLastPlayed() : ?int{
		return ($this->namedtag !== null and ($lastPlayedTag = $this->namedtag->getTag("lastPlayed")) instanceof LongTag) ? $lastPlayedTag->getValue() : null;
	}

	public function hasPlayedBefore() : bool{
		return $this->namedtag !== null;
	}
}
