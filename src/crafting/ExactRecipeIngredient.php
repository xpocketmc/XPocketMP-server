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

namespace pocketmine\crafting;

use pocketmine\item\Item;

/**
 * Recipe ingredient that matches exactly one item, without wildcards.
 * Note that recipe inputs cannot require NBT.
 */
final class ExactRecipeIngredient implements RecipeIngredient{

	public function __construct(private Item $item){
		if($item->isNull()){
			throw new \InvalidArgumentException("Recipe ingredients must not be air items");
		}
		if($item->getCount() !== 1){
			throw new \InvalidArgumentException("Recipe ingredients cannot require count");
		}
		$this->item = clone $item;
	}

	public function getItem() : Item{ return clone $this->item; }

	public function accepts(Item $item) : bool{
		//client-side, recipe inputs can't actually require NBT
		//but on the PM side, we currently check for it if the input requires it, so we have to continue to do so for
		//the sake of consistency
		return $item->getCount() >= 1 && $this->item->equals($item, true, $this->item->hasNamedTag());
	}

	public function __toString() : string{
		return "ExactRecipeIngredient(" . $this->item . ")";
	}
}
