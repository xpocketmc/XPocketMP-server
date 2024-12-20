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

namespace pocketmine\player\camera;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static CameraEaseType IN_BACK()
 * @method static CameraEaseType IN_BOUNCE()
 * @method static CameraEaseType IN_CIRC()
 * @method static CameraEaseType IN_CUBIC()
 * @method static CameraEaseType IN_ELASTIC()
 * @method static CameraEaseType IN_EXPO()
 * @method static CameraEaseType IN_OUT_BACK()
 * @method static CameraEaseType IN_OUT_BOUNCE()
 * @method static CameraEaseType IN_OUT_CIRC()
 * @method static CameraEaseType IN_OUT_CUBIC()
 * @method static CameraEaseType IN_OUT_ELASTIC()
 * @method static CameraEaseType IN_OUT_EXPO()
 * @method static CameraEaseType IN_OUT_QUAD()
 * @method static CameraEaseType IN_OUT_QUART()
 * @method static CameraEaseType IN_OUT_QUINT()
 * @method static CameraEaseType IN_OUT_SINE()
 * @method static CameraEaseType IN_QUAD()
 * @method static CameraEaseType IN_QUART()
 * @method static CameraEaseType IN_QUINT()
 * @method static CameraEaseType IN_SINE()
 * @method static CameraEaseType LINEAR()
 * @method static CameraEaseType OUT_BACK()
 * @method static CameraEaseType OUT_BOUNCE()
 * @method static CameraEaseType OUT_CIRC()
 * @method static CameraEaseType OUT_CUBIC()
 * @method static CameraEaseType OUT_ELASTIC()
 * @method static CameraEaseType OUT_EXPO()
 * @method static CameraEaseType OUT_QUAD()
 * @method static CameraEaseType OUT_QUART()
 * @method static CameraEaseType OUT_QUINT()
 * @method static CameraEaseType OUT_SINE()
 * @method static CameraEaseType SPRING()
 */
final class CameraEaseType{
	use EnumTrait;

	protected static function setup() : void{
		self::registerAll(
			new self("linear"),
			new self("spring"),
			new self("in_quad"),
			new self("out_quad"),
			new self("in_out_quad"),
			new self("in_cubic"),
			new self("out_cubic"),
			new self("in_out_cubic"),
			new self("in_quart"),
			new self("out_quart"),
			new self("in_out_quart"),
			new self("in_quint"),
			new self("out_quint"),
			new self("in_out_quint"),
			new self("in_sine"),
			new self("out_sine"),
			new self("in_out_sine"),
			new self("in_expo"),
			new self("out_expo"),
			new self("in_out_expo"),
			new self("in_circ"),
			new self("out_circ"),
			new self("in_out_circ"),
			new self("in_bounce"),
			new self("out_bounce"),
			new self("in_out_bounce"),
			new self("in_back"),
			new self("out_back"),
			new self("in_out_back"),
			new self("in_elastic"),
			new self("out_elastic"),
			new self("in_out_elastic")
		);
	}
}
