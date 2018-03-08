<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\inventory\transaction;

use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\inventory\CraftingRecipe;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\types\ContainerIds;
use pocketmine\Player;

class CraftingTransaction extends InventoryTransaction{

	protected $gridSize;
	/** @var Item[][] */
	protected $inputs;
	/** @var Item[][] */
	protected $secondaryOutputs;
	/** @var Item|null */
	protected $primaryOutput;

	/** @var CraftingRecipe|null */
	protected $recipe = null;

	public function __construct(Player $source, $actions = []){
		$this->gridSize = $source->getCraftingGrid()->getGridWidth();

		$air = ItemFactory::get(Item::AIR, 0, 0);
		$this->inputs = array_fill(0, $this->gridSize, array_fill(0, $this->gridSize, $air));
		$this->secondaryOutputs = array_fill(0, $this->gridSize, array_fill(0, $this->gridSize, $air));

		parent::__construct($source, $actions);
	}

	public function setInput(int $index, Item $item) : void{
		$y = (int) ($index / $this->gridSize);
		$x = $index % $this->gridSize;

		if(!isset($this->inputs[$y][$x])){
			return;
		}

		if($this->inputs[$y][$x]->isNull()){
			$this->inputs[$y][$x] = clone $item;
		}elseif(!$this->inputs[$y][$x]->equals($item)){
			throw new \RuntimeException("Input $index has already been set and does not match the current item (expected " . $this->inputs[$y][$x] . ", got " . $item . ")");
		}
	}

	public function getInputMap() : array{
		return $this->inputs;
	}

	public function setExtraOutput(int $index, Item $item) : void{
		$y = (int) ($index / $this->gridSize);
		$x = $index % $this->gridSize;

		if(!isset($this->secondaryOutputs[$y][$x])){
			return;
		}

		if($this->secondaryOutputs[$y][$x]->isNull()){
			$this->secondaryOutputs[$y][$x] = clone $item;
		}elseif(!$this->secondaryOutputs[$y][$x]->equals($item)){
			throw new \RuntimeException("Output $index has already been set and does not match the current item (expected " . $this->secondaryOutputs[$y][$x] . ", got " . $item . ")");
		}
	}

	public function getPrimaryOutput() : ?Item{
		return $this->primaryOutput;
	}

	public function setPrimaryOutput(Item $item) : void{
		if($this->primaryOutput === null){
			$this->primaryOutput = clone $item;
		}elseif(!$this->primaryOutput->equals($item)){
			throw new \RuntimeException("Primary result item has already been set and does not match the current item (expected " . $this->primaryOutput . ", got " . $item . ")");
		}
	}

	public function getRecipe() : ?CraftingRecipe{
		return $this->recipe;
	}

	private function reindexInputs() : array{
		$minX = 0;
		$maxX = 0;

		$minY = 0;
		$maxY = 0;

		$empty = true;

		foreach($this->inputs as $y => $row){
			foreach($row as $x => $item){
				if(!$item->isNull()){
					$minX = min($minX, $x);
					$maxX = max($maxX, $x);

					$minY = min($minY, $y);
					$maxY = max($maxY, $y);

					$empty = false;
				}
			}
		}

		if($empty){
			return [];
		}

		$air = ItemFactory::get(Item::AIR, 0, 0);
		$reindexed = array_fill(0, $maxY - $minY + 1, array_fill(0, $maxX - $minX + 1, $air));
		foreach($reindexed as $y => $row){
			foreach($row as $x => $item){
				$reindexed[$y][$x] = $this->inputs[$y + $minY][$x + $minX];
			}
		}

		return $reindexed;
	}

	public function canExecute() : bool{
		$inputs = $this->reindexInputs();

		$this->recipe = $this->source->getServer()->getCraftingManager()->matchRecipe($inputs, $this->primaryOutput, $this->secondaryOutputs);

		return $this->recipe !== null and parent::canExecute();
	}

	protected function callExecuteEvent() : bool{
		$this->source->getServer()->getPluginManager()->callEvent($ev = new CraftItemEvent($this));
		return !$ev->isCancelled();
	}

	protected function sendInventories() : void{
		parent::sendInventories();

		/*
		 * TODO: HACK!
		 * we can't resend the contents of the crafting window, so we force the client to close it instead.
		 * So people don't whine about messy desync issues when someone cancels CraftItemEvent, or when a crafting
		 * transaction goes wrong.
		 */
		$pk = new ContainerClosePacket();
		$pk->windowId = ContainerIds::NONE;
		$this->source->dataPacket($pk);
	}

	public function execute() : bool{
		if(parent::execute()){
			switch($this->primaryOutput->getId()){
				case Item::CRAFTING_TABLE:
					$this->source->awardAchievement("buildWorkBench");
					break;
				case Item::WOODEN_PICKAXE:
					$this->source->awardAchievement("buildPickaxe");
					break;
				case Item::FURNACE:
					$this->source->awardAchievement("buildFurnace");
					break;
				case Item::WOODEN_HOE:
					$this->source->awardAchievement("buildHoe");
					break;
				case Item::BREAD:
					$this->source->awardAchievement("makeBread");
					break;
				case Item::CAKE:
					$this->source->awardAchievement("bakeCake");
					break;
				case Item::STONE_PICKAXE:
				case Item::GOLDEN_PICKAXE:
				case Item::IRON_PICKAXE:
				case Item::DIAMOND_PICKAXE:
					$this->source->awardAchievement("buildBetterPickaxe");
					break;
				case Item::WOODEN_SWORD:
					$this->source->awardAchievement("buildSword");
					break;
				case Item::DIAMOND:
					$this->source->awardAchievement("diamond");
					break;
			}

			return true;
		}

		return false;
	}
}
