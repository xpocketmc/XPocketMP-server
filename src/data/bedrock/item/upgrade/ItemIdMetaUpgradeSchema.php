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

namespace pocketmine\data\bedrock\item\upgrade;

use function mb_strtolower;

final class ItemIdMetaUpgradeSchema{

	/**
	 * @param string[]   $renamedIds
	 * @param string[][] $remappedMetas
	 * @phpstan-param array<string, string> $renamedIds
	 * @phpstan-param array<string, array<int, string>> $remappedMetas
	 */
	public function __construct(
		private array $renamedIds,
		private array $remappedMetas,
		private int $schemaId
	){}

	public function getSchemaId() : int{ return $this->schemaId; }

	/**
	 * @return string[]
	 * @phpstan-return array<string, string>
	 */
	public function getRenamedIds() : array{ return $this->renamedIds; }

	/**
	 * @return string[][]
	 * @phpstan-return array<string, array<int, string>>
	 */
	public function getRemappedMetas() : array{ return $this->remappedMetas; }

	public function renameId(string $id) : ?string{
		return $this->renamedIds[mb_strtolower($id, 'US-ASCII')] ?? null;
	}

	public function remapMeta(string $id, int $meta) : ?string{
		return $this->remappedMetas[mb_strtolower($id, 'US-ASCII')][$meta] ?? null;
	}
}
