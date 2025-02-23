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

namespace pocketmine\entity\attribute;

use function array_filter;

class AttributeMap{
	/** @var Attribute[] */
	private array $attributes = [];

	public function add(Attribute $attribute) : void{
		$this->attributes[$attribute->getId()] = $attribute;
	}

	public function get(string $id) : ?Attribute{
		return $this->attributes[$id] ?? null;
	}

	/**
	 * @return Attribute[]
	 */
	public function getAll() : array{
		return $this->attributes;
	}

	/**
	 * @return Attribute[]
	 */
	public function needSend() : array{
		return array_filter($this->attributes, function(Attribute $attribute) : bool{
			return $attribute->isSyncable() && $attribute->isDesynchronized();
		});
	}
}
