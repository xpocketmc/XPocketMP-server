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

namespace pocketmine\crafting;

use pocketmine\data\bedrock\ItemTagToIdMap;
use pocketmine\item\Item;
use pocketmine\world\format\io\GlobalItemDataHandlers;

/**
 * Recipe ingredient that matches items whose ID falls within a specific set. This is used for magic meta value
 * wildcards and also for ingredients which use item tags (since tags implicitly rely on ID only).
 *
 * @internal
 */
final class TagWildcardRecipeIngredient implements RecipeIngredient{

	public function __construct(
		private string $tagName
	){}

	public function getTagName() : string{ return $this->tagName; }

	public function accepts(Item $item) : bool{
		if($item->getCount() < 1){
			return false;
		}

		return ItemTagToIdMap::getInstance()->tagContainsId($this->tagName, GlobalItemDataHandlers::getSerializer()->serializeType($item)->getName());
	}

	public function __toString() : string{
		return "TagWildcardRecipeIngredient($this->tagName)";
	}
}
