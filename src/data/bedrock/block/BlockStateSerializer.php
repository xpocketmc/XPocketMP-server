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

namespace pocketmine\data\bedrock\block;

/**
 * Implementors of this interface decide how blockstate IDs will be represented as NBT.
 *
 * @phpstan-type BlockStateId int
 */
interface BlockStateSerializer{

	/**
	 * Serializes an implementation-defined blockstate ID to NBT for storage.
	 *
	 * @phpstan-param BlockStateId $stateId
	 * @throws BlockStateSerializeException
	 */
	public function serialize(int $stateId) : BlockStateData;
}
