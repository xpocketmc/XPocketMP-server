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
 * Developed: ClousClouds, PMMP Team
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\data\bedrock\block\upgrade;

use pocketmine\data\bedrock\LegacyToStringIdMap;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;

final class LegacyBlockIdToStringIdMap extends LegacyToStringIdMap{
	use SingletonTrait;

	public function __construct(){
		parent::__construct(Path::join(\pocketmine\BEDROCK_BLOCK_UPGRADE_SCHEMA_PATH, 'block_legacy_id_map.json'));
	}
}
