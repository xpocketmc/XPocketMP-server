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
 * Developed: ClousClouds, PMMP Team
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerToggleGlideEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\scheduler\ClosureTask;

class Elytra extends Armor{

	public const MINIMUM_PITCH = -59;
	public const MAXIMUM_PITCH = 38;

	private array $glidingTicker = [];

	public function __construct(ItemIdentifier $identifier, string $name){
		parent::__construct($identifier, $name, new ArmorTypeInfo(0, 432, ArmorInventory::SLOT_CHEST));
	}

	public function getMaxDurability() : int{
		return 432;
	}

	public function applyDamage(int $amount) : bool{
		$this->setDamage($this->getDamage() + $amount);
		return $this->getDamage() >= $this->getMaxDurability();
	}

	public function onPlayerItemUse(PlayerItemUseEvent $event) : void{
		$player = $event->getPlayer();
		$inventory = $player->getInventory();
		$item = $inventory->getItemInHand();
		if(!$item instanceof Fireworks){
			return;
		}
		if(!$player->isGliding()){
			return;
		}
		$item->pop();
		$location = $player->getLocation();
		$entity = new FireworksRocket($location, $item);

		$entity->getNetworkProperties()->setLong(EntityMetadataProperties::MINECART_HAS_DISPLAY, $player->getId());
		$entity->setOwningEntity($player);
		$entity->spawnToAll();

		$inventory->setItemInHand($item);
	}

	public function onPlayerMove(PlayerMoveEvent $event) : void{
		$player = $event->getPlayer();
		if(!player->isGliding()){
			return;
		}

		$location = $event->getId();
		if($location->pitch >= self::MINIMUM_PITCH && $location->pitch <= self::MAXIMUM_PUTCH){
			$player->resetFallDistence();
		}
	}

	public function onPlayerToggleGlide(PlayerToggleGlideEvent $event) : void{
		$player = $event->getPlayer();
		$rawUUID = $player->getUniqueId()->getBytes();
		if($event->isGliding()){
			$armorInventory = $player->getArmorInventory();
			$this->glidingTicker[$rawUUID] = $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(static function() use($armorInventory, $player) : void{
				if($player->hasFiniteResources() && ($elytra = $armorInventory->getChestplate()) instanceof Elytra && $elytra->applyDamage(1)){
					$armorInventory->setChestplate($elytra);
				}
			}), 20);
		}else{
			($this->glidingTicker[$rawUUID] ?? null)?->cancel();
			unset($this->glidingTicker[$rawUUID]);
		}
	}

	public function onPlayerQuit(PlayerQuitEvent $event) : void{
		$rawUUID = $event->getPlayer()->getUniqueId()->getBytes();
		($this->glidingTicker[$rawUUID] ?? null)?->cancel();

		unset($this->glidingTicker[$rawUUID]);
	}
}
