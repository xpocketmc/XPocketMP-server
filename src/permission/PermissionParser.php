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

namespace pocketmine\permission;

use pocketmine\utils\Utils;
use function is_bool;
use function strtolower;

class PermissionParser{

	public const DEFAULT_OP = "op";
	public const DEFAULT_NOT_OP = "notop";
	public const DEFAULT_TRUE = "true";
	public const DEFAULT_FALSE = "false";

	public const DEFAULT_STRING_MAP = [
		"op" => self::DEFAULT_OP,
		"isop" => self::DEFAULT_OP,
		"operator" => self::DEFAULT_OP,
		"isoperator" => self::DEFAULT_OP,
		"admin" => self::DEFAULT_OP,
		"isadmin" => self::DEFAULT_OP,

		"!op" => self::DEFAULT_NOT_OP,
		"notop" => self::DEFAULT_NOT_OP,
		"!operator" => self::DEFAULT_NOT_OP,
		"notoperator" => self::DEFAULT_NOT_OP,
		"!admin" => self::DEFAULT_NOT_OP,
		"notadmin" => self::DEFAULT_NOT_OP,

		"true" => self::DEFAULT_TRUE,
		"false" => self::DEFAULT_FALSE,
	];

	private const KEY_DEFAULT = "default";
	private const KEY_CHILDREN = "children";
	private const KEY_DESCRIPTION = "description";

	/**
	 * @throws PermissionParserException
	 */
	public static function defaultFromString(bool|string $value) : string{
		if(is_bool($value)){
			return $value ? self::DEFAULT_TRUE : self::DEFAULT_FALSE;
		}
		$lower = strtolower($value);
		if(isset(self::DEFAULT_STRING_MAP[$lower])){
			return self::DEFAULT_STRING_MAP[$lower];
		}

		throw new PermissionParserException("Unknown permission default name \"$value\"");
	}

	/**
	 * @param mixed[][] $data
	 * @phpstan-param array<string, array<string, mixed>> $data
	 *
	 * @return Permission[][]
	 * @phpstan-return array<string, list<Permission>>
	 * @throws PermissionParserException
	 */
	public static function loadPermissions(array $data, string $default = self::DEFAULT_FALSE) : array{
		$result = [];
		foreach(Utils::stringifyKeys($data) as $name => $entry){
			$desc = null;
			if(isset($entry[self::KEY_DEFAULT])){
				$default = PermissionParser::defaultFromString($entry[self::KEY_DEFAULT]);
			}

			if(isset($entry[self::KEY_CHILDREN])){
				throw new PermissionParserException("Nested permission declarations are no longer supported. Declare each permission separately.");
			}

			if(isset($entry[self::KEY_DESCRIPTION])){
				$desc = $entry[self::KEY_DESCRIPTION];
			}

			$result[$default][] = new Permission($name, $desc);
		}
		return $result;
	}
}
