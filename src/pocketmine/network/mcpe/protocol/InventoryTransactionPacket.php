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

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\inventory\source\InventorySource;

class InventoryTransactionPacket extends DataPacket{
	const NETWORK_ID = ProtocolInfo::INVENTORY_TRANSACTION_PACKET;

	const TYPE_USE_ITEM = 2;
	const TYPE_USE_ITEM_ON_ENTITY = 3;
	const TYPE_RELEASE_ITEM = 4;

	const USE_ITEM_ACTION_CLICK_BLOCK = 0;
	const USE_ITEM_ACTION_CLICK_AIR = 1;
	const USE_ITEM_ACTION_BREAK_BLOCK = 2;

	const RELEASE_ITEM_ACTION_RELEASE = 0; //bow shoot
	const RELEASE_ITEM_ACTION_CONSUME = 1; //eat food, drink potion

	//TODO: use-item on entity interaction action types

	const SOURCE_CONTAINER = 0;

	const SOURCE_WORLD = 2; //drop/pickup item entity
	const SOURCE_CREATIVE = 3;

	const SOURCE_TODO = 99999;

	/**
	 * These identifiers are used for special slot types for transaction/inventory types that are not yet implemented.
	 * Expect these to change in the future.
	 */
	const SOURCE_TYPE_CRAFTING_ADD_INGREDIENT = -2;
	const SOURCE_TYPE_CRAFTING_REMOVE_INGREDIENT = -3;
	const SOURCE_TYPE_CRAFTING_RESULT = -4;

	const SOURCE_TYPE_ANVIL_INPUT = -10;
	const SOURCE_TYPE_ANVIL_MATERIAL = -11;
	const SOURCE_TYPE_ANVIL_RESULT = -12;
	const SOURCE_TYPE_ANVIL_OUTPUT = -13;

	const SOURCE_TYPE_ENCHANT_INPUT = -15;
	const SOURCE_TYPE_ENCHANT_MATERIAL = -16;
	const SOURCE_TYPE_ENCHANT_OUTPUT = -17;

	const SOURCE_TYPE_TRADING_INPUT_1 = -20;
	const SOURCE_TYPE_TRADING_INPUT_2 = -21;
	const SOURCE_TYPE_TRADING_USE_INPUTS = -22;
	const SOURCE_TYPE_TRADING_OUTPUT = -23;

	const SOURCE_TYPE_BEACON = -24;

	const SOURCE_TYPE_CONTAINER_DROP_CONTENTS = -100;





	public $actions = [];

	public $transactionData;

	protected function decodePayload(){
		$type = $this->getUnsignedVarInt();

		$actionCount = $this->getUnsignedVarInt();
		for($i = 0; $i < $actionCount; ++$i){
			$this->actions[] = $this->decodeInventoryAction();
		}

		$this->transactionData = new \stdClass();
		$this->transactionData->transactionType = $type;

		switch($type){
			case 0:
			case 1:
				//Regular ComplexInventoryTransaction doesn't read any extra data
				break;
			case self::TYPE_USE_ITEM:
				$this->transactionData->useItemActionType = $this->getUnsignedVarInt();
				$this->getBlockPosition($this->transactionData->x, $this->transactionData->y, $this->transactionData->z);
				$this->transactionData->face = $this->getVarInt();
				$this->transactionData->hotbarSlot = $this->getVarInt();
				$this->transactionData->itemInHand = $this->getSlot();
				$this->transactionData->playerPos = $this->getVector3Obj();
				$this->transactionData->clickPos = $this->getVector3Obj();
				break;
			case self::TYPE_USE_ITEM_ON_ENTITY:
				$this->transactionData->entityRuntimeId = $this->getEntityRuntimeId();
				$this->transactionData->uvarint1_3 = $this->getUnsignedVarInt(); //probably action type, check values
				$this->transactionData->hotbarSlot = $this->getVarInt();
				$this->transactionData->itemInHand = $this->getSlot();
				$this->transactionData->vector1 = $this->getVector3Obj();
				$this->transactionData->vector2 = $this->getVector3Obj();
				break;
			case self::TYPE_RELEASE_ITEM:
				$this->transactionData->releaseItemActionType = $this->getUnsignedVarInt();
				$this->transactionData->hotbarSlot = $this->getVarInt();
				$this->transactionData->itemInHand = $this->getSlot();
				$this->transactionData->headPos = $this->getVector3Obj();
				break;
			default:
				throw new \UnexpectedValueException("Unknown transaction type $type");


		}

		//TODO
	}

	protected function encodePayload(){
		//TODO
	}

	protected function decodeInventoryAction(){
		$actionBucket = new \stdClass();
		$actionBucket->inventorySource = $this->decodeInventorySource();

		$actionBucket->inventorySlot = $this->getUnsignedVarInt();
		$actionBucket->oldItem = $this->getSlot();
		$actionBucket->newItem = $this->getSlot();
		return $actionBucket;
	}

	protected function decodeInventorySource(){
		$bucket = new \stdClass();
		$bucket->sourceType = $this->getUnsignedVarInt();

		switch($bucket->sourceType){
			case self::SOURCE_CONTAINER:

				$bucket->containerId = $this->getVarInt();
				$bucket->field_2 = 0;
				break;
			case 1:
				break; //unused
			case self::SOURCE_WORLD:
				$bucket->containerId = -1;
				$bucket->field_2 = $this->getUnsignedVarInt();
				break;
			case self::SOURCE_CREATIVE:
				$bucket->containerId = -1;
				$bucket->field_2 = 0;
				break;
			case self::SOURCE_TODO:
				$bucket->containerId = $this->getVarInt();
				$bucket->field_2 = 0;
				break;
			default:
				throw new \UnexpectedValueException("Unexpected inventory source type $bucket->sourceType");

		}

		return $bucket;
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleInventoryTransaction($this);
	}
}