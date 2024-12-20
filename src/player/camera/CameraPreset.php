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

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\camera\element\CameraState;

final class CameraPreset{

	private const TAG_IDENTIFIER = "identifier"; //TAG_String
	private const TAG_INHERIT_FROM = "inherit_from"; //TAG_String

	private const TAG_POSITION_X = "pos_x"; //TAG_Float
	private const TAG_POSITION_Y = "pos_y"; //TAG_Float
	private const TAG_POSITION_Z = "pos_z"; //TAG_Float

	private const TAG_ROTATION_YAW = "rot_x"; //TAG_Float
	private const TAG_ROTATION_PITCH = "rot_y"; //TAG_Float

	public function __construct(
		private string $identifier,
		private string $inheritFrom = "",
		private ?CameraState $state = null
	){
	}

	public function getIdentifier() : string{
		return $this->identifier;
	}

	public function getInheritFrom() : string{
		return $this->inheritFrom;
	}

	public function getCameraState() : ?CameraState{
		return $this->state;
	}

	public function toCompoundTag() : CompoundTag{
		$tag = CompoundTag::create()
			->setString(self::TAG_IDENTIFIER, $this->identifier)
			->setString(self::TAG_INHERIT_FROM, $this->inheritFrom);

		if ($this->state !== null) {
			if (($position = $this->state->getPosition()) !== null) {
				$tag->setFloat(self::TAG_POSITION_X, $position->x)
					->setFloat(self::TAG_POSITION_Y, $position->y)
					->setFloat(self::TAG_POSITION_Z, $position->z);
			}
			if (($yaw = $this->state->getYaw()) !== null && ($pitch = $this->state->getPitch()) !== null) {
				$tag->setFloat(self::TAG_ROTATION_YAW, $yaw)
					->setFloat(self::TAG_ROTATION_PITCH, $pitch);
			}
		}

		return $tag;
	}

}
