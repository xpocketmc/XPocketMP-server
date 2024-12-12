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

namespace pocketmine\crafting\json;

final class PotionContainerChangeRecipeData{
	/** @required */
	public string $input_item_name;

	/** @required */
	public RecipeIngredientData $ingredient;

	/** @required */
	public string $output_item_name;

	public function __construct(string $input_item_name, RecipeIngredientData $ingredient, string $output_item_name){
		$this->input_item_name = $input_item_name;
		$this->ingredient = $ingredient;
		$this->output_item_name = $output_item_name;
	}
}
