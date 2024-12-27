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

namespace pocketmine\item;

use pocketmine\utils\Limits;
use pocketmine\utils\Utils;
use function sprintf;
use function strlen;

class WritableBookPage{
	public const PAGE_LENGTH_HARD_LIMIT_BYTES = Limits::INT16_MAX;
	public const PHOTO_NAME_LENGTH_HARD_LIMIT_BYTES = Limits::INT16_MAX;

	private string $text;
	private string $photoName;

	/**
	 * @throws \InvalidArgumentException
	 */
	private static function checkLength(string $string, string $name, int $maxLength) : void{
		if(strlen($string) > $maxLength){
			throw new \InvalidArgumentException(sprintf("$name must be at most %d bytes, but have %d bytes", $maxLength, strlen($string)));
		}
	}

	public function __construct(string $text, string $photoName = ""){
		self::checkLength($text, "Text", self::PAGE_LENGTH_HARD_LIMIT_BYTES);
		self::checkLength($photoName, "Photo name", self::PHOTO_NAME_LENGTH_HARD_LIMIT_BYTES);
		Utils::checkUTF8($text);
		$this->text = $text;
		$this->photoName = $photoName;
	}

	public function getText() : string{
		return $this->text;
	}

	public function getPhotoName() : string{
		return $this->photoName;
	}
}
