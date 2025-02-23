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

namespace pocketmine\network\mcpe\handler;

use pocketmine\entity\attribute\Attribute;
use pocketmine\entity\attribute\AttributeMap;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\cache\CraftingDataCache;
use pocketmine\network\mcpe\cache\StaticPacketCache;
use pocketmine\network\mcpe\inventory\InventoryManager;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\ItemRegistryPacket;
use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;
use pocketmine\network\mcpe\protocol\RequestChunkRadiusPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\network\mcpe\protocol\types\Experiments;
use pocketmine\network\mcpe\protocol\types\LevelSettings;
use pocketmine\network\mcpe\protocol\types\NetworkPermissions;
use pocketmine\network\mcpe\protocol\types\PlayerMovementSettings;
use pocketmine\network\mcpe\protocol\types\ServerAuthMovementMode;
use pocketmine\network\mcpe\protocol\types\SpawnSettings;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\timings\Timings;
use pocketmine\VersionInfo;
use Ramsey\Uuid\Uuid;
use function sprintf;

/**
 * Handler used for the pre-spawn phase of the session.
 */
class PreSpawnPacketHandler extends PacketHandler{
	public function __construct(
		private Server $server,
		private Player $player,
		private NetworkSession $session,
		private InventoryManager $inventoryManager
	){}

	public function setUp() : void{
		Timings::$playerNetworkSendPreSpawnGameData->startTiming();
		try{
			$location = $this->player->getLocation();
			$world = $location->getWorld();

			$typeConverter = $this->session->getTypeConverter();

			$this->session->getLogger()->debug("Preparing StartGamePacket");
			$levelSettings = new LevelSettings();
			$levelSettings->seed = -1;
			$levelSettings->spawnSettings = new SpawnSettings(SpawnSettings::BIOME_TYPE_DEFAULT, "", DimensionIds::OVERWORLD); //TODO: implement this properly
			$levelSettings->worldGamemode = $typeConverter->coreGameModeToProtocol($this->server->getGamemode());
			$levelSettings->difficulty = $world->getDifficulty();
			$levelSettings->spawnPosition = BlockPosition::fromVector3($world->getSpawnLocation());
			$levelSettings->hasAchievementsDisabled = true;
			$levelSettings->time = $world->getTime();
			$levelSettings->eduEditionOffer = 0;
			$levelSettings->rainLevel = 0; //TODO: implement these properly
			$levelSettings->lightningLevel = 0;
			$levelSettings->commandsEnabled = true;
			$levelSettings->gameRules = [
				"naturalregeneration" => new BoolGameRule(false, false) //Hack for client side regeneration
			];
			$levelSettings->experiments = new Experiments([], false);

			$this->session->sendDataPacket(StartGamePacket::create(
				$this->player->getId(),
				$this->player->getId(),
				$typeConverter->coreGameModeToProtocol($this->player->getGamemode()),
				$this->player->getOffsetPosition($location),
				$location->pitch,
				$location->yaw,
				new CacheableNbt(CompoundTag::create()), //TODO: we don't care about this right now
				$levelSettings,
				"",
				$this->server->getMotd(),
				"",
				false,
				new PlayerMovementSettings(ServerAuthMovementMode::SERVER_AUTHORITATIVE_V2, 0, false),
				0,
				0,
				"",
				true,
				sprintf("%s %s", VersionInfo::NAME, VersionInfo::VERSION()->getFullVersion(true)),
				Uuid::fromString(Uuid::NIL),
				false,
				false,
				new NetworkPermissions(disableClientSounds: true),
				[],
				0,
			));
	
			$this->session->getLogger()->debug("Sending items");
			$this->session->sendDataPacket(ItemRegistryPacket::create($typeConverter->getItemTypeDictionary()->getEntries()));

			$this->session->getLogger()->debug("Sending actor identifiers");
			$this->session->sendDataPacket(StaticPacketCache::getInstance()->getAvailableActorIdentifiers());

			$this->session->getLogger()->debug("Sending biome definitions");
			$this->session->sendDataPacket(StaticPacketCache::getInstance()->getBiomeDefs());

			$this->session->getLogger()->debug("Sending attributes");
			$this->session->getEntityEventBroadcaster()->syncAttributes([$this->session], $this->player, $this->player->getAttributeMap()->getAll());

			$this->session->getLogger()->debug("Sending available commands");
			$this->session->syncAvailableCommands();

			$this->session->getLogger()->debug("Sending abilities");
			$this->session->syncAbilities($this->player);
			$this->session->syncAdventureSettings();

			$this->session->getLogger()->debug("Sending effects");
			foreach($this->player->getEffects()->all() as $effect){
				$this->session->getEntityEventBroadcaster()->onEntityEffectAdded([$this->session], $this->player, $effect, false);
			}

			$this->session->getLogger()->debug("Sending actor metadata");
			$this->player->sendData([$this->player]);

			$this->session->getLogger()->debug("Sending inventory");
			$this->inventoryManager->syncAll();
			$this->inventoryManager->syncSelectedHotbarSlot();

			$this->session->getLogger()->debug("Sending creative inventory data");
			$this->inventoryManager->syncCreative();

			$this->session->getLogger()->debug("Sending crafting data");
			$this->session->sendDataPacket(CraftingDataCache::getInstance()->getCache($this->server->getCraftingManager()));

			$this->session->getLogger()->debug("Sending player list");
			$this->session->syncPlayerList($this->server->getOnlinePlayers());
		}finally{
			Timings::$playerNetworkSendPreSpawnGameData->stopTiming();
		}
	}

	public function handleRequestChunkRadius(RequestChunkRadiusPacket $packet) : bool{
		$this->player->setViewDistance($packet->radius);

		return true;
	}

	public function handlePlayerAuthInput(PlayerAuthInputPacket $packet) : bool{
		//the client will send this every tick once we start sending chunks, but we don't handle it in this stage
		//this is very spammy so we filter it out
		return true;
	}
}
