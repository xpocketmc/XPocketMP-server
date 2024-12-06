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
 * @author XPocketMP Team
 * @link http://www.xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace XPocketMPlock\inventory;

use XPocketMP\event\player\PlayerEnchantingOptionsRequestEvent;
use XPocketMP\inventory\SimpleInventory;
use XPocketMP\inventory\TemporaryInventory;
use XPocketMP\item\enchantment\EnchantingHelper as Helper;
use XPocketMP\item\enchantment\EnchantingOption;
use XPocketMP\item\Item;
use XPocketMP\world\Position;
use function array_values;
use function count;

class EnchantInventory extends SimpleInventory implements BlockInventory, TemporaryInventory{
	use BlockInventoryTrait;

	public const SLOT_INPUT = 0;
	public const SLOT_LAPIS = 1;

	/**
	 * @var EnchantingOption[] $options
	 * @phpstan-var list<EnchantingOption>
	 */
	private array $options = [];

	public function __construct(Position $holder){
		$this->holder = $holder;
		parent::__construct(2);
	}

	protected function onSlotChange(int $index, Item $before) : void{
		if($index === self::SLOT_INPUT){
			foreach($this->viewers as $viewer){
				$this->options = [];
				$item = $this->getInput();
				$options = Helper::generateOptions($this->holder, $item, $viewer->getEnchantmentSeed());

				$event = new PlayerEnchantingOptionsRequestEvent($viewer, $this, $options);
				$event->call();
				if(!$event->isCancelled() && count($event->getOptions()) > 0){
					$this->options = array_values($event->getOptions());
					$viewer->getNetworkSession()->getInvManager()?->syncEnchantingTableOptions($this->options);
				}
			}
		}

		parent::onSlotChange($index, $before);
	}

	public function getInput() : Item{
		return $this->getItem(self::SLOT_INPUT);
	}

	public function getLapis() : Item{
		return $this->getItem(self::SLOT_LAPIS);
	}

	public function getOutput(int $optionId) : ?Item{
		$option = $this->getOption($optionId);
		return $option === null ? null : Helper::enchantItem($this->getInput(), $option->getEnchantments());
	}

	public function getOption(int $optionId) : ?EnchantingOption{
		return $this->options[$optionId] ?? null;
	}
}