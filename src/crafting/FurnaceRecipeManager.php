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
use pocketmine\utils\ObjectSet;

final class FurnaceRecipeManager{
	/** @var FurnaceRecipe[] */
	protected array $furnaceRecipes = [];

	/**
	 * @var FurnaceRecipe[]
	 * @phpstan-var array<int, FurnaceRecipe>
	 */
	private array $lookupCache = [];

	/** @phpstan-var ObjectSet<\Closure(FurnaceRecipe) : void> */
	private ObjectSet $recipeRegisteredCallbacks;

	public function __construct(){
		$this->recipeRegisteredCallbacks = new ObjectSet();
	}

	/**
	 * @phpstan-return ObjectSet<\Closure(FurnaceRecipe) : void>
	 */
	public function getRecipeRegisteredCallbacks() : ObjectSet{
		return $this->recipeRegisteredCallbacks;
	}

	/**
	 * @return FurnaceRecipe[]
	 */
	public function getAll() : array{
		return $this->furnaceRecipes;
	}

	public function register(FurnaceRecipe $recipe) : void{
		$this->furnaceRecipes[] = $recipe;
		foreach($this->recipeRegisteredCallbacks as $callback){
			$callback($recipe);
		}
	}

	public function match(Item $input) : ?FurnaceRecipe{
		$index = $input->getStateId();
		$simpleRecipe = $this->lookupCache[$index] ?? null;
		if($simpleRecipe !== null){
			return $simpleRecipe;
		}

		foreach($this->furnaceRecipes as $recipe){
			if($recipe->getInput()->accepts($input)){
				//remember that this item is accepted by this recipe, so we don't need to bruteforce it again
				$this->lookupCache[$index] = $recipe;
				return $recipe;
			}
		}

		return null;
	}
}
