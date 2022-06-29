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

namespace pocketmine\network\mcpe;

use pocketmine\block\inventory\AnvilInventory;
use pocketmine\block\inventory\BlockInventory;
use pocketmine\block\inventory\BrewingStandInventory;
use pocketmine\block\inventory\CraftingTableInventory;
use pocketmine\block\inventory\EnchantInventory;
use pocketmine\block\inventory\FurnaceInventory;
use pocketmine\block\inventory\HopperInventory;
use pocketmine\block\inventory\LoomInventory;
use pocketmine\block\inventory\StonecutterInventory;
use pocketmine\crafting\FurnaceType;
use pocketmine\inventory\CreativeInventory;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\inventory\transaction\InventoryTransaction;
use pocketmine\item\Item;
use pocketmine\network\mcpe\convert\TypeConversionException;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\ContainerSetDataPacket;
use pocketmine\network\mcpe\protocol\CreativeContentPacket;
use pocketmine\network\mcpe\protocol\InventoryContentPacket;
use pocketmine\network\mcpe\protocol\InventorySlotPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;
use pocketmine\network\mcpe\protocol\types\inventory\CreativeContentEntry;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\network\mcpe\protocol\types\inventory\NetworkInventoryAction;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\network\PacketHandlingException;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\ObjectSet;
use function array_map;
use function array_search;
use function get_class;
use function max;
use function spl_object_id;

/**
 * @phpstan-type ContainerOpenClosure \Closure(int $id, Inventory $inventory) : (list<ClientboundPacket>|null)
 */
class InventoryManager{
	/** @var Inventory[] */
	private array $windowMap = [];
	private int $lastInventoryNetworkId = ContainerIds::FIRST;

	/**
	 * @var Item[][]
	 * @phpstan-var array<int, array<int, Item>>
	 */
	private array $initiatedSlotChanges = [];
	private int $clientSelectedHotbarSlot = -1;

	/** @phpstan-var ObjectSet<ContainerOpenClosure> */
	private ObjectSet $containerOpenCallbacks;

	private ?int $pendingCloseWindowId = null;
	/** @phpstan-var \Closure() : void */
	private ?\Closure $pendingOpenWindowCallback = null;

	private ?CreativeContentPacket $creativeContentsCache = null;

	public function __construct(
		private Player $player,
		private NetworkSession $session
	){
		$this->containerOpenCallbacks = new ObjectSet();
		$this->containerOpenCallbacks->add(\Closure::fromCallable([self::class, 'createContainerOpen']));

		$this->add(ContainerIds::INVENTORY, $this->player->getInventory());
		$this->add(ContainerIds::OFFHAND, $this->player->getOffHandInventory());
		$this->add(ContainerIds::ARMOR, $this->player->getArmorInventory());
		$this->add(ContainerIds::UI, $this->player->getCursorInventory());

		$this->player->getInventory()->getHeldItemIndexChangeListeners()->add(function() : void{
			$this->syncSelectedHotbarSlot();
		});
	}

	private function add(int $id, Inventory $inventory) : void{
		$this->windowMap[$id] = $inventory;
	}

	private function addDynamic(Inventory $inventory) : int{
		$this->lastInventoryNetworkId = max(ContainerIds::FIRST, ($this->lastInventoryNetworkId + 1) % ContainerIds::LAST);
		$this->add($this->lastInventoryNetworkId, $inventory);
		return $this->lastInventoryNetworkId;
	}

	private function remove(int $id) : void{
		unset($this->windowMap[$id], $this->initiatedSlotChanges[$id]);
	}

	public function getWindowId(Inventory $inventory) : ?int{
		return ($id = array_search($inventory, $this->windowMap, true)) !== false ? $id : null;
	}

	public function getCurrentWindowId() : int{
		return $this->lastInventoryNetworkId;
	}

	public function getWindow(int $windowId) : ?Inventory{
		return $this->windowMap[$windowId] ?? null;
	}

	public function onTransactionStart(InventoryTransaction $tx) : void{
		foreach($tx->getActions() as $action){
			if($action instanceof SlotChangeAction && ($windowId = $this->getWindowId($action->getInventory())) !== null){
				//in some cases the inventory might not have a window ID, but still be referenced by a transaction (e.g. crafting grid changes), so we can't unconditionally record the change here or we might leak things
				$this->initiatedSlotChanges[$windowId][$action->getSlot()] = $action->getTargetItem();
			}
		}
	}

	/**
	 * @param NetworkInventoryAction[] $networkInventoryActions
	 * @throws PacketHandlingException
	 */
	public function addPredictedSlotChanges(array $networkInventoryActions) : void{
		foreach($networkInventoryActions as $action){
			if($action->sourceType === NetworkInventoryAction::SOURCE_CONTAINER && isset($this->windowMap[$action->windowId])){
				//this won't cover stuff like crafting grid due to too much magic
				try{
					$item = TypeConverter::getInstance()->netItemStackToCore($action->newItem->getItemStack());
				}catch(TypeConversionException $e){
					throw new PacketHandlingException($e->getMessage(), 0, $e);
				}
				$this->initiatedSlotChanges[$action->windowId][$action->inventorySlot] = $item;
			}
		}
	}

	/**
	 * When the server initiates a window close, it does so by sending a ContainerClose to the client, which causes the
	 * client to behave as if it initiated the close itself. It responds by sending a ContainerClose back to the server,
	 * which the server is then expected to respond to.
	 *
	 * Sending the client a new window before sending this final response creates buggy behaviour on the client, which
	 * is problematic when switching windows. Therefore, we defer sending any new windows until after the client
	 * responds to our window close instruction, so that we can complete the window handshake correctly.
	 *
	 * This is a pile of complicated garbage that only exists because Mojang overengineered the process of opening and
	 * closing inventory windows.
	 *
	 * @phpstan-param \Closure() : void $func
	 */
	private function openWindowDeferred(\Closure $func) : void{
		if($this->pendingCloseWindowId !== null){
			$this->session->getLogger()->debug("Deferring opening of new window, waiting for close ack of window $this->pendingCloseWindowId");
			$this->pendingOpenWindowCallback = $func;
		}else{
			$func();
		}
	}

	public function onCurrentWindowChange(Inventory $inventory) : void{
		$this->onCurrentWindowRemove();

		$this->openWindowDeferred(function() use ($inventory) : void{
			$windowId = $this->addDynamic($inventory);

			foreach($this->containerOpenCallbacks as $callback){
				$pks = $callback($windowId, $inventory);
				if($pks !== null){
					foreach($pks as $pk){
						$this->session->sendDataPacket($pk);
					}
					$this->syncContents($inventory);
					return;
				}
			}
			throw new \LogicException("Unsupported inventory type");
		});
	}

	/** @phpstan-return ObjectSet<ContainerOpenClosure> */
	public function getContainerOpenCallbacks() : ObjectSet{ return $this->containerOpenCallbacks; }

	/**
	 * @return ClientboundPacket[]|null
	 * @phpstan-return list<ClientboundPacket>|null
	 */
	protected static function createContainerOpen(int $id, Inventory $inv) : ?array{
		//TODO: we should be using some kind of tagging system to identify the types. Instanceof is flaky especially
		//if the class isn't final, not to mention being inflexible.
		if($inv instanceof BlockInventory){
			$blockPosition = BlockPosition::fromVector3($inv->getHolder());
			$windowType = match(true){
				$inv instanceof LoomInventory => WindowTypes::LOOM,
				$inv instanceof FurnaceInventory => match($inv->getFurnaceType()->id()){
						FurnaceType::FURNACE()->id() => WindowTypes::FURNACE,
						FurnaceType::BLAST_FURNACE()->id() => WindowTypes::BLAST_FURNACE,
						FurnaceType::SMOKER()->id() => WindowTypes::SMOKER,
						default => throw new AssumptionFailedError("Unreachable")
					},
				$inv instanceof EnchantInventory => WindowTypes::ENCHANTMENT,
				$inv instanceof BrewingStandInventory => WindowTypes::BREWING_STAND,
				$inv instanceof AnvilInventory => WindowTypes::ANVIL,
				$inv instanceof HopperInventory => WindowTypes::HOPPER,
				$inv instanceof CraftingTableInventory => WindowTypes::WORKBENCH,
				$inv instanceof StonecutterInventory => WindowTypes::STONECUTTER,
				default => WindowTypes::CONTAINER
			};
			return [ContainerOpenPacket::blockInv($id, $windowType, $blockPosition)];
		}
		return null;
	}

	public function onClientOpenMainInventory() : void{
		$this->onCurrentWindowRemove();

		$this->openWindowDeferred(function() : void{
			$windowId = $this->addDynamic($this->player->getInventory());

			$this->session->sendDataPacket(ContainerOpenPacket::entityInv(
				$windowId,
				WindowTypes::INVENTORY,
				$this->player->getId()
			));
		});
	}

	public function onCurrentWindowRemove() : void{
		if(isset($this->windowMap[$this->lastInventoryNetworkId])){
			$this->remove($this->lastInventoryNetworkId);
			$this->session->sendDataPacket(ContainerClosePacket::create($this->lastInventoryNetworkId, true));
			if($this->pendingCloseWindowId !== null){
				throw new AssumptionFailedError("We should not have opened a new window while a window was waiting to be closed");
			}
			$this->pendingCloseWindowId = $this->lastInventoryNetworkId;
		}
	}

	public function onClientRemoveWindow(int $id) : void{
		if($id === $this->lastInventoryNetworkId){
			$this->remove($id);
			$this->player->removeCurrentWindow();
		}else{
			$this->session->getLogger()->debug("Attempted to close inventory with network ID $id, but current is $this->lastInventoryNetworkId");
		}

		//Always send this, even if no window matches. If we told the client to close a window, it will behave as if it
		//initiated the close and expect an ack.
		$this->session->sendDataPacket(ContainerClosePacket::create($id, false));

		if($this->pendingCloseWindowId === $id){
			$this->pendingCloseWindowId = null;
			if($this->pendingOpenWindowCallback !== null){
				$this->session->getLogger()->debug("Opening deferred window after close ack of window $id");
				($this->pendingOpenWindowCallback)();
				$this->pendingOpenWindowCallback = null;
			}
		}
	}

	public function syncSlot(Inventory $inventory, int $slot) : void{
		$windowId = $this->getWindowId($inventory);
		if($windowId !== null){
			$currentItem = $inventory->getItem($slot);
			$clientSideItem = $this->initiatedSlotChanges[$windowId][$slot] ?? null;
			if($clientSideItem === null || !$clientSideItem->equalsExact($currentItem)){
				$itemStackWrapper = ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($currentItem));
				if($windowId === ContainerIds::OFFHAND){
					//TODO: HACK!
					//The client may sometimes ignore the InventorySlotPacket for the offhand slot.
					//This can cause a lot of problems (totems, arrows, and more...).
					//The workaround is to send an InventoryContentPacket instead
					//BDS (Bedrock Dedicated Server) also seems to work this way.
					$this->session->sendDataPacket(InventoryContentPacket::create($windowId, [$itemStackWrapper]));
				}else{
					$this->session->sendDataPacket(InventorySlotPacket::create($windowId, $slot, $itemStackWrapper));
				}
			}
			unset($this->initiatedSlotChanges[$windowId][$slot]);
		}
	}

	public function syncContents(Inventory $inventory) : void{
		$windowId = $this->getWindowId($inventory);
		if($windowId !== null){
			unset($this->initiatedSlotChanges[$windowId]);
			$typeConverter = TypeConverter::getInstance();
			if($windowId === ContainerIds::UI){
				//TODO: HACK!
				//Since 1.13, cursor is now part of a larger "UI inventory", and sending contents for this larger inventory does
				//not work the way it's intended to. Even if it did, it would be necessary to send all 51 slots just to update
				//this one, which is just not worth it.
				//This workaround isn't great, but it's at least simple.
				$this->session->sendDataPacket(InventorySlotPacket::create(
					$windowId,
					0,
					ItemStackWrapper::legacy($typeConverter->coreItemStackToNet($inventory->getItem(0)))
				));
			}else{
				$this->session->sendDataPacket(InventoryContentPacket::create($windowId, array_map(function(Item $itemStack) use ($typeConverter) : ItemStackWrapper{
					return ItemStackWrapper::legacy($typeConverter->coreItemStackToNet($itemStack));
				}, $inventory->getContents(true))));
			}
		}
	}

	public function syncAll() : void{
		foreach($this->windowMap as $inventory){
			$this->syncContents($inventory);
		}
	}

	public function syncMismatchedPredictedSlotChanges() : void{
		foreach($this->initiatedSlotChanges as $windowId => $slots){
			if(!isset($this->windowMap[$windowId])){
				continue;
			}
			$inventory = $this->windowMap[$windowId];

			foreach($slots as $slot => $expectedItem){
				if(!$inventory->slotExists($slot)){
					continue; //TODO: size desync ???
				}
				$actualItem = $inventory->getItem($slot);
				if(!$actualItem->equalsExact($expectedItem)){
					$this->session->getLogger()->debug("Detected prediction mismatch in inventory " . get_class($inventory) . "#" . spl_object_id($inventory) . " slot $slot");
					$this->syncSlot($inventory, $slot);
				}
			}
		}

		$this->initiatedSlotChanges = [];
	}

	public function syncData(Inventory $inventory, int $propertyId, int $value) : void{
		$windowId = $this->getWindowId($inventory);
		if($windowId !== null){
			$this->session->sendDataPacket(ContainerSetDataPacket::create($windowId, $propertyId, $value));
		}
	}

	public function onClientSelectHotbarSlot(int $slot) : void{
		$this->clientSelectedHotbarSlot = $slot;
	}

	public function syncSelectedHotbarSlot() : void{
		$selected = $this->player->getInventory()->getHeldItemIndex();
		if($selected !== $this->clientSelectedHotbarSlot){
			$this->session->sendDataPacket(MobEquipmentPacket::create(
				$this->player->getId(),
				ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($this->player->getInventory()->getItemInHand())),
				$selected,
				$selected,
				ContainerIds::INVENTORY
			));
			$this->clientSelectedHotbarSlot = $selected;
		}
	}

	public function syncCreative() : void{
		$creativeInventory = CreativeInventory::getInstance();

		if($this->creativeContentsCache === null || $creativeInventory->hasChanged()){
			$typeConverter = TypeConverter::getInstance();
			$nextEntryId = 1;

			$this->creativeContentsCache = CreativeContentPacket::create(
				array_map(function(Item $item) use($typeConverter, &$nextEntryId) : CreativeContentEntry{
					return new CreativeContentEntry($nextEntryId++, $typeConverter->coreItemStackToNet($item));
				}, $creativeInventory->getAll())
			);

			$creativeInventory->setChanged(false);
		}

		$this->session->sendDataPacket($this->player->isSpectator() ? CreativeContentPacket::create([]) : clone $this->creativeContentsCache);
	}
}
