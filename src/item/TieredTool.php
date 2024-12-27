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

abstract class TieredTool extends Tool{
	protected ToolTier $tier;

	/**
	 * @param string[] $enchantmentTags
	 */
	public function __construct(ItemIdentifier $identifier, string $name, ToolTier $tier, array $enchantmentTags = []){
		parent::__construct($identifier, $name, $enchantmentTags);
		$this->tier = $tier;
	}

	public function getMaxDurability() : int{
		return $this->tier->getMaxDurability();
	}

	public function getTier() : ToolTier{
		return $this->tier;
	}

	protected function getBaseMiningEfficiency() : float{
		return $this->tier->getBaseEfficiency();
	}

	public function getEnchantability() : int{
		return $this->tier->getEnchantability();
	}

	public function getFuelTime() : int{
		if($this->tier === ToolTier::WOOD){
			return 200;
		}

		return 0;
	}

	public function isFireProof() : bool{
		return $this->tier === ToolTier::NETHERITE;
	}
}
