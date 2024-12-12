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

namespace pocketmine\data\bedrock\item;

use PHPUnit\Framework\TestCase;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\item\VanillaItems;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

final class ItemSerializerDeserializerTest extends TestCase{

	private ItemDeserializer $deserializer;
	private ItemSerializer $serializer;

	public function setUp() : void{
		$this->deserializer = new ItemDeserializer(GlobalBlockStateHandlers::getDeserializer());
		$this->serializer = new ItemSerializer(GlobalBlockStateHandlers::getSerializer());
	}

	public function testAllVanillaItemsSerializableAndDeserializable() : void{
		foreach(VanillaItems::getAll() as $item){
			if($item->isNull()){
				continue;
			}

			try{
				$itemData = $this->serializer->serializeType($item);
			}catch(ItemTypeSerializeException $e){
				self::fail($e->getMessage());
			}
			try{
				$newItem = $this->deserializer->deserializeType($itemData);
			}catch(ItemTypeDeserializeException $e){
				self::fail($e->getMessage());
			}

			self::assertTrue($item->equalsExact($newItem));
		}
	}

	public function testAllVanillaBlocksSerializableAndDeserializable() : void{
		foreach(RuntimeBlockStateRegistry::getInstance()->getAllKnownStates() as $block){
			$item = $block->asItem();
			if($item->isNull()){
				continue;
			}

			try{
				$itemData = $this->serializer->serializeType($item);
			}catch(ItemTypeSerializeException $e){
				self::fail($e->getMessage());
			}
			try{
				$newItem = $this->deserializer->deserializeType($itemData);
			}catch(ItemTypeDeserializeException $e){
				self::fail($e->getMessage());
			}

			self::assertTrue($item->equalsExact($newItem));
		}
	}
}
