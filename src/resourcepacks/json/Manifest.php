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

namespace pocketmine\resourcepacks\json;

/**
 * Model for JsonMapper to represent resource pack manifest.json contents.
 */
final class Manifest{
	/** @required */
	public int $format_version;

	/** @required */
	public ManifestHeader $header;

	/**
	 * @var ManifestModuleEntry[]
	 * @required
	 */
	public array $modules;

	public ?ManifestMetadata $metadata = null;

	/** @var string[] */
	public ?array $capabilities = null;

	/** @var ManifestDependencyEntry[] */
	public ?array $dependencies = null;
}
