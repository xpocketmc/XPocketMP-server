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

namespace pocketmine\network\mcpe\handler;

use pocketmine\inventory\Inventory;
use pocketmine\network\mcpe\InventoryManager;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerUIIds;
use pocketmine\network\mcpe\protocol\types\inventory\stackresponse\ItemStackResponse;
use pocketmine\network\mcpe\protocol\types\inventory\stackresponse\ItemStackResponseContainerInfo;
use pocketmine\network\mcpe\protocol\types\inventory\stackresponse\ItemStackResponseSlotInfo;
use pocketmine\utils\AssumptionFailedError;

final class ItemStackResponseBuilder{

	/**
	 * @var int[][]
	 * @phpstan-var array<int, array<int, int>>
	 */
	private array $changedSlots = [];

	public function __construct(
		private int $requestId,
		private InventoryManager $inventoryManager
	){}

	public function addSlot(int $containerInterfaceId, int $slotId) : void{
		$this->changedSlots[$containerInterfaceId][$slotId] = $slotId;
	}

	/**
	 * @phpstan-return array{Inventory, int}
	 */
	private function getInventoryAndSlot(int $containerInterfaceId, int $slotId) : ?array{
		$windowId = ItemStackContainerIdTranslator::translate($containerInterfaceId, $this->inventoryManager->getCurrentWindowId());
		$windowAndSlot = $this->inventoryManager->locateWindowAndSlot($windowId, $slotId);
		if($windowAndSlot === null){
			return null;
		}
		[$inventory, $slot] = $windowAndSlot;
		if(!$inventory->slotExists($slot)){
			return null;
		}

		return [$inventory, $slot];
	}

	public function build(bool $success) : ItemStackResponse{
		$responseInfosByContainer = [];
		foreach($this->changedSlots as $containerInterfaceId => $slotIds){
			if($containerInterfaceId === ContainerUIIds::CREATED_OUTPUT){
				continue;
			}
			foreach($slotIds as $slotId){
				$inventoryAndSlot = $this->getInventoryAndSlot($containerInterfaceId, $slotId);
				if($inventoryAndSlot === null){
					//a plugin may have closed the inventory during an event, or the slot may have been invalid
					continue;
				}
				[$inventory, $slot] = $inventoryAndSlot;

				$itemStackInfo = $this->inventoryManager->getItemStackInfo($inventory, $slot);
				if($itemStackInfo === null){
					throw new AssumptionFailedError("ItemStackInfo should never be null for an open inventory");
				}
				if($itemStackInfo->getRequestId() !== $this->requestId){
					//the itemstack may have been synced due to transaction producing results that the client did not
					//predict correctly, which will wipe out the tracked request ID (intentionally)
					//TODO: is this the correct behaviour?
					continue;
				}
				$item = $inventory->getItem($slot);

				$responseInfosByContainer[$containerInterfaceId][] = new ItemStackResponseSlotInfo(
					$slotId,
					$slotId,
					$item->getCount(),
					$itemStackInfo->getStackId(),
					$item->getCustomName(),
					0
				);
			}
		}

		$responseContainerInfos = [];
		foreach($responseInfosByContainer as $containerInterfaceId => $responseInfos){
			$responseContainerInfos[] = new ItemStackResponseContainerInfo($containerInterfaceId, $responseInfos);
		}

		return new ItemStackResponse($success ? ItemStackResponse::RESULT_OK : ItemStackResponse::RESULT_ERROR, $this->requestId, $responseContainerInfos);
	}
}
