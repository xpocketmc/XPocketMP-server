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
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\permission;

class PermissionAttachmentInfo{
	public function __construct(
		private string $permission,
		private ?PermissionAttachment $attachment,
		private bool $value,
		private ?PermissionAttachmentInfo $groupPermission
	){}

	public function getPermission() : string{
		return $this->permission;
	}

	public function getAttachment() : ?PermissionAttachment{
		return $this->attachment;
	}

	public function getValue() : bool{
		return $this->value;
	}

	/**
	 * Returns the info of the permission group that caused this permission to be set, if any.
	 * If null, the permission was set explicitly, either by a permission attachment or base permission.
	 */
	public function getGroupPermissionInfo() : ?PermissionAttachmentInfo{ return $this->groupPermission; }
}
