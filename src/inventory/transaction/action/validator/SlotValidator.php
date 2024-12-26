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

namespace pocketmine\inventory\transaction\action\validator;

use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\TransactionValidationException;
use pocketmine\item\Item;

/**
 * Validates a slot placement in an inventory.
 */
interface SlotValidator{
	/**
	 * Returns null if the slot placement is valid, or a TransactionValidationException if it is not.
	 */
	public function validate(Inventory $inventory, Item $item, int $slot) : ?TransactionValidationException;
}
