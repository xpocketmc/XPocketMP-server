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

namespace pocketmine\data\bedrock\block;

/**
 * Implementors of this interface decide how a block should be deserialized and represented at runtime. This is used by
 * world providers when decoding blockstates into block IDs.
 *
 * @phpstan-type BlockStateId int
 */
interface BlockStateDeserializer{
	/**
	 * Deserializes blockstate NBT into an implementation-defined blockstate ID, for runtime paletted storage.
	 *
	 * @phpstan-return BlockStateId
	 * @throws BlockStateDeserializeException
	 */
	public function deserialize(BlockStateData $stateData) : int;
}
