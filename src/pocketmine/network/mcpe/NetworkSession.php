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

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\Living;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\form\Form;
use pocketmine\GameMode;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\network\BadPacketException;
use pocketmine\network\mcpe\handler\DeathSessionHandler;
use pocketmine\network\mcpe\handler\HandshakeSessionHandler;
use pocketmine\network\mcpe\handler\LoginSessionHandler;
use pocketmine\network\mcpe\handler\PreSpawnSessionHandler;
use pocketmine\network\mcpe\handler\ResourcePacksSessionHandler;
use pocketmine\network\mcpe\handler\SessionHandler;
use pocketmine\network\mcpe\handler\SimpleSessionHandler;
use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\ChunkRadiusUpdatedPacket;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\DisconnectPacket;
use pocketmine\network\mcpe\protocol\MobEffectPacket;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\NetworkChunkPublisherUpdatePacket;
use pocketmine\network\mcpe\protocol\Packet;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\network\mcpe\protocol\PlayStatusPacket;
use pocketmine\network\mcpe\protocol\ServerboundPacket;
use pocketmine\network\mcpe\protocol\ServerToClientHandshakePacket;
use pocketmine\network\mcpe\protocol\SetPlayerGameTypePacket;
use pocketmine\network\mcpe\protocol\SetSpawnPositionPacket;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\TransferPacket;
use pocketmine\network\mcpe\protocol\types\CommandData;
use pocketmine\network\mcpe\protocol\types\CommandEnum;
use pocketmine\network\mcpe\protocol\types\CommandParameter;
use pocketmine\network\mcpe\protocol\types\PlayerPermissions;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\network\NetworkInterface;
use pocketmine\network\NetworkSessionManager;
use pocketmine\Player;
use pocketmine\PlayerInfo;
use pocketmine\Server;
use pocketmine\timings\Timings;
use pocketmine\utils\BinaryDataException;
use function bin2hex;
use function count;
use function get_class;
use function in_array;
use function json_encode;
use function json_last_error_msg;
use function strlen;
use function strtolower;
use function substr;
use function time;
use function ucfirst;

class NetworkSession{
	/** @var Server */
	private $server;
	/** @var Player|null */
	private $player = null;
	/** @var NetworkSessionManager */
	private $manager;
	/** @var NetworkInterface */
	private $interface;
	/** @var string */
	private $ip;
	/** @var int */
	private $port;
	/** @var PlayerInfo */
	private $info;
	/** @var int */
	private $ping;

	/** @var SessionHandler */
	private $handler;

	/** @var bool */
	private $connected = true;
	/** @var bool */
	private $disconnectGuard = false;
	/** @var bool */
	private $loggedIn = false;
	/** @var bool */
	private $authenticated = false;
	/** @var int */
	private $connectTime;

	/** @var NetworkCipher */
	private $cipher;

	/** @var PacketStream|null */
	private $sendBuffer;

	/** @var \SplQueue|CompressBatchPromise[] */
	private $compressedQueue;

	public function __construct(Server $server, NetworkSessionManager $manager, NetworkInterface $interface, string $ip, int $port){
		$this->server = $server;
		$this->manager = $manager;
		$this->interface = $interface;
		$this->ip = $ip;
		$this->port = $port;

		$this->compressedQueue = new \SplQueue();

		$this->connectTime = time();

		$this->setHandler(new LoginSessionHandler($this->server, $this));

		$this->manager->add($this);
	}

	protected function createPlayer() : void{
		$ev = new PlayerCreationEvent($this);
		$ev->call();
		$class = $ev->getPlayerClass();

		/**
		 * @var Player $player
		 * @see Player::__construct()
		 */
		$this->player = new $class($this->server, $this, $this->info, $this->authenticated);
	}

	public function getPlayer() : ?Player{
		return $this->player;
	}

	public function getPlayerInfo() : ?PlayerInfo{
		return $this->info;
	}

	/**
	 * TODO: this shouldn't be accessible after the initial login phase
	 *
	 * @param PlayerInfo $info
	 * @throws \InvalidStateException
	 */
	public function setPlayerInfo(PlayerInfo $info) : void{
		if($this->info !== null){
			throw new \InvalidStateException("Player info has already been set");
		}
		$this->info = $info;
	}

	public function isConnected() : bool{
		return $this->connected;
	}

	public function getInterface() : NetworkInterface{
		return $this->interface;
	}

	/**
	 * @return string
	 */
	public function getIp() : string{
		return $this->ip;
	}

	/**
	 * @return int
	 */
	public function getPort() : int{
		return $this->port;
	}

	public function getDisplayName() : string{
		return $this->info !== null ? $this->info->getUsername() : $this->ip . " " . $this->port;
	}

	/**
	 * Returns the last recorded ping measurement for this session, in milliseconds.
	 *
	 * @return int
	 */
	public function getPing() : int{
		return $this->ping;
	}

	/**
	 * @internal Called by the network interface to update last recorded ping measurements.
	 *
	 * @param int $ping
	 */
	public function updatePing(int $ping) : void{
		$this->ping = $ping;
	}

	public function getHandler() : SessionHandler{
		return $this->handler;
	}

	public function setHandler(SessionHandler $handler) : void{
		if($this->connected){ //TODO: this is fine since we can't handle anything from a disconnected session, but it might produce surprises in some cases
			$this->handler = $handler;
			$this->handler->setUp();
		}
	}

	/**
	 * @param string $payload
	 *
	 * @throws BadPacketException
	 */
	public function handleEncoded(string $payload) : void{
		if(!$this->connected){
			return;
		}

		if($this->cipher !== null){
			Timings::$playerNetworkReceiveDecryptTimer->startTiming();
			try{
				$payload = $this->cipher->decrypt($payload);
			}catch(\UnexpectedValueException $e){
				$this->server->getLogger()->debug("Encrypted packet from " . $this->getDisplayName() . ": " . bin2hex($payload));
				throw new BadPacketException("Packet decryption error: " . $e->getMessage(), 0, $e);
			}finally{
				Timings::$playerNetworkReceiveDecryptTimer->stopTiming();
			}
		}

		Timings::$playerNetworkReceiveDecompressTimer->startTiming();
		try{
			$stream = new PacketStream(NetworkCompression::decompress($payload));
		}catch(\ErrorException $e){
			$this->server->getLogger()->debug("Failed to decompress packet from " . $this->getDisplayName() . ": " . bin2hex($payload));
			//TODO: this isn't incompatible game version if we already established protocol version
			throw new BadPacketException("Compressed packet batch decode error (incompatible game version?)", 0, $e);
		}finally{
			Timings::$playerNetworkReceiveDecompressTimer->stopTiming();
		}

		$count = 0;
		while(!$stream->feof() and $this->connected){
			if($count++ >= 500){
				throw new BadPacketException("Too many packets in a single batch");
			}
			try{
				$pk = PacketPool::getPacket($stream->getString());
			}catch(BinaryDataException $e){
				$this->server->getLogger()->debug("Packet batch from " . $this->getDisplayName() . ": " . bin2hex($stream->getBuffer()));
				throw new BadPacketException("Packet batch decode error: " . $e->getMessage(), 0, $e);
			}

			try{
				$this->handleDataPacket($pk);
			}catch(BadPacketException $e){
				$this->server->getLogger()->debug($pk->getName() . " from " . $this->getDisplayName() . ": " . bin2hex($pk->getBuffer()));
				throw new BadPacketException("Error processing " . $pk->getName() . ": " . $e->getMessage(), 0, $e);
			}
		}
	}

	/**
	 * @param Packet $packet
	 *
	 * @throws BadPacketException
	 */
	public function handleDataPacket(Packet $packet) : void{
		if(!($packet instanceof ServerboundPacket)){
			throw new BadPacketException("Unexpected non-serverbound packet");
		}

		$timings = Timings::getReceiveDataPacketTimings($packet);
		$timings->startTiming();

		try{
			$packet->decode();
			if(!$packet->feof() and !$packet->mayHaveUnreadBytes()){
				$remains = substr($packet->getBuffer(), $packet->getOffset());
				$this->server->getLogger()->debug("Still " . strlen($remains) . " bytes unread in " . $packet->getName() . ": " . bin2hex($remains));
			}

			$ev = new DataPacketReceiveEvent($this, $packet);
			$ev->call();
			if(!$ev->isCancelled() and !$packet->handle($this->handler)){
				$this->server->getLogger()->debug("Unhandled " . $packet->getName() . " received from " . $this->getDisplayName() . ": " . bin2hex($packet->getBuffer()));
			}
		}finally{
			$timings->stopTiming();
		}
	}

	public function sendDataPacket(ClientboundPacket $packet, bool $immediate = false) : bool{
		//Basic safety restriction. TODO: improve this
		if(!$this->loggedIn and !$packet->canBeSentBeforeLogin()){
			throw new \InvalidArgumentException("Attempted to send " . get_class($packet) . " to " . $this->getDisplayName() . " too early");
		}

		$timings = Timings::getSendDataPacketTimings($packet);
		$timings->startTiming();
		try{
			$ev = new DataPacketSendEvent($this, $packet);
			$ev->call();
			if($ev->isCancelled()){
				return false;
			}

			$this->addToSendBuffer($packet);
			if($immediate){
				$this->flushSendBuffer(true);
			}

			return true;
		}finally{
			$timings->stopTiming();
		}
	}

	/**
	 * @internal
	 * @param ClientboundPacket $packet
	 */
	public function addToSendBuffer(ClientboundPacket $packet) : void{
		$timings = Timings::getSendDataPacketTimings($packet);
		$timings->startTiming();
		try{
			if($this->sendBuffer === null){
				$this->sendBuffer = new PacketStream();
			}
			$this->sendBuffer->putPacket($packet);
			$this->manager->scheduleUpdate($this); //schedule flush at end of tick
		}finally{
			$timings->stopTiming();
		}
	}

	private function flushSendBuffer(bool $immediate = false) : void{
		if($this->sendBuffer !== null){
			$promise = $this->server->prepareBatch($this->sendBuffer, $immediate);
			$this->sendBuffer = null;
			$this->queueCompressed($promise, $immediate);
		}
	}

	public function queueCompressed(CompressBatchPromise $payload, bool $immediate = false) : void{
		$this->flushSendBuffer($immediate); //Maintain ordering if possible
		if($immediate){
			//Skips all queues
			$this->sendEncoded($payload->getResult(), true);
		}else{
			$this->compressedQueue->enqueue($payload);
			$payload->onResolve(function(CompressBatchPromise $payload) : void{
				if($this->connected and $this->compressedQueue->bottom() === $payload){
					$this->compressedQueue->dequeue(); //result unused
					$this->sendEncoded($payload->getResult());

					while(!$this->compressedQueue->isEmpty()){
						/** @var CompressBatchPromise $current */
						$current = $this->compressedQueue->bottom();
						if($current->hasResult()){
							$this->compressedQueue->dequeue();

							$this->sendEncoded($current->getResult());
						}else{
							//can't send any more queued until this one is ready
							break;
						}
					}
				}
			});
		}
	}

	private function sendEncoded(string $payload, bool $immediate = false) : void{
		if($this->cipher !== null){
			Timings::$playerNetworkSendEncryptTimer->startTiming();
			$payload = $this->cipher->encrypt($payload);
			Timings::$playerNetworkSendEncryptTimer->stopTiming();
		}
		$this->interface->putPacket($this, $payload, $immediate);
	}

	private function tryDisconnect(\Closure $func) : void{
		if($this->connected and !$this->disconnectGuard){
			$this->disconnectGuard = true;
			$func();
			$this->disconnectGuard = false;
			$this->connected = false;
			$this->manager->remove($this);
		}
	}

	/**
	 * Disconnects the session, destroying the associated player (if it exists).
	 *
	 * @param string $reason
	 * @param bool   $notify
	 */
	public function disconnect(string $reason, bool $notify = true) : void{
		$this->tryDisconnect(function() use($reason, $notify){
			if($this->player !== null){
				$this->player->disconnect($reason, null, $notify);
			}
			$this->doServerDisconnect($reason, $notify);
		});
	}

	/**
	 * Instructs the remote client to connect to a different server.
	 *
	 * @param string $ip
	 * @param int    $port
	 * @param string $reason
	 *
	 * @throws \UnsupportedOperationException
	 */
	public function transfer(string $ip, int $port, string $reason = "transfer") : void{
		$this->tryDisconnect(function() use($ip, $port, $reason){
			$pk = new TransferPacket();
			$pk->address = $ip;
			$pk->port = $port;
			$this->sendDataPacket($pk, true);
			$this->disconnect($reason, false);
			if($this->player !== null){
				$this->player->disconnect($reason, null, false);
			}
			$this->doServerDisconnect($reason, false);
		});
	}

	/**
	 * Called by the Player when it is closed (for example due to getting kicked).
	 *
	 * @param string $reason
	 * @param bool   $notify
	 */
	public function onPlayerDestroyed(string $reason, bool $notify = true) : void{
		$this->tryDisconnect(function() use($reason, $notify){
			$this->doServerDisconnect($reason, $notify);
		});
	}

	/**
	 * Internal helper function used to handle server disconnections.
	 *
	 * @param string $reason
	 * @param bool   $notify
	 */
	private function doServerDisconnect(string $reason, bool $notify = true) : void{
		if($notify){
			$pk = new DisconnectPacket();
			$pk->message = $reason;
			$pk->hideDisconnectionScreen = $reason === "";
			$this->sendDataPacket($pk, true);
		}

		$this->interface->close($this, $notify ? $reason : "");
	}

	/**
	 * Called by the network interface to close the session when the client disconnects without server input, for
	 * example in a timeout condition or voluntary client disconnect.
	 *
	 * @param string $reason
	 */
	public function onClientDisconnect(string $reason) : void{
		$this->tryDisconnect(function() use($reason){
			if($this->player !== null){
				$this->player->disconnect($reason, null, false);
			}
		});
	}

	public function setAuthenticationStatus(bool $authenticated, bool $authRequired, ?string $error) : bool{
		if($authenticated and $this->info->getXuid() === ""){
			$error = "Expected XUID but none found";
		}

		if($error !== null){
			$this->disconnect($this->server->getLanguage()->translateString("pocketmine.disconnect.invalidSession", [$error]));

			return false;
		}

		$this->authenticated = $authenticated;

		if(!$this->authenticated){
			if($authRequired){
				$this->disconnect("disconnectionScreen.notAuthenticated");
				return false;
			}

			$this->server->getLogger()->debug($this->getDisplayName() . " is NOT logged into Xbox Live");
			if($this->info->getXuid() !== ""){
				$this->server->getLogger()->warning($this->getDisplayName() . " has an XUID, but their login keychain is not signed by Mojang");
			}
		}else{
			$this->server->getLogger()->debug($this->getDisplayName() . " is logged into Xbox Live");
		}

		return $this->manager->kickDuplicates($this);
	}

	public function enableEncryption(string $encryptionKey, string $handshakeJwt) : void{
		$pk = new ServerToClientHandshakePacket();
		$pk->jwt = $handshakeJwt;
		$this->sendDataPacket($pk, true); //make sure this gets sent before encryption is enabled

		$this->cipher = new NetworkCipher($encryptionKey);

		$this->setHandler(new HandshakeSessionHandler($this));
		$this->server->getLogger()->debug("Enabled encryption for " . $this->getDisplayName());
	}

	public function onLoginSuccess() : void{
		$this->loggedIn = true;

		$pk = new PlayStatusPacket();
		$pk->status = PlayStatusPacket::LOGIN_SUCCESS;
		$this->sendDataPacket($pk);

		$this->setHandler(new ResourcePacksSessionHandler($this->server, $this, $this->server->getResourcePackManager()));
	}

	public function onResourcePacksDone() : void{
		$this->createPlayer();

		$this->setHandler(new PreSpawnSessionHandler($this->server, $this->player, $this));
	}

	public function onTerrainReady() : void{
		$pk = new PlayStatusPacket();
		$pk->status = PlayStatusPacket::PLAYER_SPAWN;
		$this->sendDataPacket($pk);
	}

	public function onSpawn() : void{
		$this->setHandler(new SimpleSessionHandler($this->player, $this));
	}

	public function onDeath() : void{
		$this->setHandler(new DeathSessionHandler($this->player, $this));
	}

	public function onRespawn() : void{
		$this->setHandler(new SimpleSessionHandler($this->player, $this));
	}

	public function syncMovement(Vector3 $pos, ?float $yaw = null, ?float $pitch = null, int $mode = MovePlayerPacket::MODE_NORMAL) : void{
		$yaw = $yaw ?? $this->player->getYaw();
		$pitch = $pitch ?? $this->player->getPitch();

		$pk = new MovePlayerPacket();
		$pk->entityRuntimeId = $this->player->getId();
		$pk->position = $this->player->getOffsetPosition($pos);
		$pk->pitch = $pitch;
		$pk->headYaw = $yaw;
		$pk->yaw = $yaw;
		$pk->mode = $mode;

		$this->sendDataPacket($pk);
	}

	public function syncViewAreaRadius(int $distance) : void{
		$pk = new ChunkRadiusUpdatedPacket();
		$pk->radius = $distance;
		$this->sendDataPacket($pk);
	}

	public function syncViewAreaCenterPoint(Vector3 $newPos, int $viewDistance) : void{
		$pk = new NetworkChunkPublisherUpdatePacket();
		$pk->x = $newPos->getFloorX();
		$pk->y = $newPos->getFloorY();
		$pk->z = $newPos->getFloorZ();
		$pk->radius = $viewDistance * 16; //blocks, not chunks >.>
		$this->sendDataPacket($pk);
	}

	public function syncPlayerSpawnPoint(Position $newSpawn) : void{
		$pk = new SetSpawnPositionPacket();
		$pk->x = $newSpawn->getFloorX();
		$pk->y = $newSpawn->getFloorY();
		$pk->z = $newSpawn->getFloorZ();
		$pk->spawnType = SetSpawnPositionPacket::TYPE_PLAYER_SPAWN;
		$pk->spawnForced = false;
		$this->sendDataPacket($pk);
	}

	public function syncGameMode(GameMode $mode) : void{
		$pk = new SetPlayerGameTypePacket();
		$pk->gamemode = self::getClientFriendlyGamemode($mode);
		$this->sendDataPacket($pk);
	}

	/**
	 * TODO: make this less specialized
	 *
	 * @param Player $for
	 */
	public function syncAdventureSettings(Player $for) : void{
		$pk = new AdventureSettingsPacket();

		$pk->setFlag(AdventureSettingsPacket::WORLD_IMMUTABLE, $for->isSpectator());
		$pk->setFlag(AdventureSettingsPacket::NO_PVP, $for->isSpectator());
		$pk->setFlag(AdventureSettingsPacket::AUTO_JUMP, $for->hasAutoJump());
		$pk->setFlag(AdventureSettingsPacket::ALLOW_FLIGHT, $for->getAllowFlight());
		$pk->setFlag(AdventureSettingsPacket::NO_CLIP, $for->isSpectator());
		$pk->setFlag(AdventureSettingsPacket::FLYING, $for->isFlying());

		//TODO: permission flags

		$pk->commandPermission = ($for->isOp() ? AdventureSettingsPacket::PERMISSION_OPERATOR : AdventureSettingsPacket::PERMISSION_NORMAL);
		$pk->playerPermission = ($for->isOp() ? PlayerPermissions::OPERATOR : PlayerPermissions::MEMBER);
		$pk->entityUniqueId = $for->getId();

		$this->sendDataPacket($pk);
	}

	public function syncAttributes(Living $entity, bool $sendAll = false){
		$entries = $sendAll ? $entity->getAttributeMap()->getAll() : $entity->getAttributeMap()->needSend();
		if(count($entries) > 0){
			$pk = new UpdateAttributesPacket();
			$pk->entityRuntimeId = $entity->getId();
			$pk->entries = $entries;
			$this->sendDataPacket($pk);
			foreach($entries as $entry){
				$entry->markSynchronized();
			}
		}
	}

	public function onEntityEffectAdded(Living $entity, EffectInstance $effect, bool $replacesOldEffect) : void{
		$pk = new MobEffectPacket();
		$pk->entityRuntimeId = $entity->getId();
		$pk->eventId = $replacesOldEffect ? MobEffectPacket::EVENT_MODIFY : MobEffectPacket::EVENT_ADD;
		$pk->effectId = $effect->getId();
		$pk->amplifier = $effect->getAmplifier();
		$pk->particles = $effect->isVisible();
		$pk->duration = $effect->getDuration();

		$this->sendDataPacket($pk);
	}

	public function onEntityEffectRemoved(Living $entity, EffectInstance $effect) : void{
		$pk = new MobEffectPacket();
		$pk->entityRuntimeId = $entity->getId();
		$pk->eventId = MobEffectPacket::EVENT_REMOVE;
		$pk->effectId = $effect->getId();

		$this->sendDataPacket($pk);
	}

	public function syncAvailableCommands() : void{
		$pk = new AvailableCommandsPacket();
		foreach($this->server->getCommandMap()->getCommands() as $name => $command){
			if(isset($pk->commandData[$command->getName()]) or $command->getName() === "help" or !$command->testPermissionSilent($this->player)){
				continue;
			}

			$data = new CommandData();
			//TODO: commands containing uppercase letters in the name crash 1.9.0 client
			$data->commandName = strtolower($command->getName());
			$data->commandDescription = $this->server->getLanguage()->translateString($command->getDescription());
			$data->flags = 0;
			$data->permission = 0;

			$parameter = new CommandParameter();
			$parameter->paramName = "args";
			$parameter->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
			$parameter->isOptional = true;
			$data->overloads[0][0] = $parameter;

			$aliases = $command->getAliases();
			if(!empty($aliases)){
				if(!in_array($data->commandName, $aliases, true)){
					//work around a client bug which makes the original name not show when aliases are used
					$aliases[] = $data->commandName;
				}
				$data->aliases = new CommandEnum();
				$data->aliases->enumName = ucfirst($command->getName()) . "Aliases";
				$data->aliases->enumValues = $aliases;
			}

			$pk->commandData[$command->getName()] = $data;
		}

		$this->sendDataPacket($pk);
	}

	public function onRawChatMessage(string $message) : void{
		$pk = new TextPacket();
		$pk->type = TextPacket::TYPE_RAW;
		$pk->message = $message;
		$this->sendDataPacket($pk);
	}

	public function onTranslatedChatMessage(string $key, array $parameters) : void{
		$pk = new TextPacket();
		$pk->type = TextPacket::TYPE_TRANSLATION;
		$pk->needsTranslation = true;
		$pk->message = $key;
		$pk->parameters = $parameters;
		$this->sendDataPacket($pk);
	}

	public function onPopup(string $message) : void{
		$pk = new TextPacket();
		$pk->type = TextPacket::TYPE_POPUP;
		$pk->message = $message;
		$this->sendDataPacket($pk);
	}

	public function onTip(string $message) : void{
		$pk = new TextPacket();
		$pk->type = TextPacket::TYPE_TIP;
		$pk->message = $message;
		$this->sendDataPacket($pk);
	}

	public function onFormSent(int $id, Form $form) : bool{
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$pk->formData = json_encode($form);
		if($pk->formData === false){
			throw new \InvalidArgumentException("Failed to encode form JSON: " . json_last_error_msg());
		}
		return $this->sendDataPacket($pk);
	}

	public function startUsingChunk(int $chunkX, int $chunkZ, bool $spawn = false) : void{
		ChunkCache::getInstance($this->player->getLevel())->request($chunkX, $chunkZ)->onResolve(

			//this callback may be called synchronously or asynchronously, depending on whether the promise is resolved yet
			function(CompressBatchPromise $promise) use($chunkX, $chunkZ, $spawn){
				if(!$this->isConnected()){
					return;
				}
				$this->player->level->timings->syncChunkSendTimer->startTiming();
				try{
					$this->queueCompressed($promise);

					foreach($this->player->getLevel()->getChunkEntities($chunkX, $chunkZ) as $entity){
						if($entity !== $this->player and !$entity->isClosed() and !$entity->isFlaggedForDespawn()){
							$entity->spawnTo($this->player);
						}
					}

					if($spawn){
						//TODO: potential race condition during chunk sending could cause this to be called too early
						$this->onTerrainReady();
					}
				}finally{
					$this->player->level->timings->syncChunkSendTimer->stopTiming();
				}
			}
		);
	}

	public function stopUsingChunk(int $chunkX, int $chunkZ) : void{
		foreach($this->player->getLevel()->getChunkEntities($chunkX, $chunkZ) as $entity){
			if($entity !== $this->player){
				$entity->despawnFrom($this->player);
			}
		}
	}

	public function tick() : bool{
		if($this->handler instanceof LoginSessionHandler){
			if(time() >= $this->connectTime + 10){
				$this->disconnect("Login timeout");
				return false;
			}

			return true; //keep ticking until timeout
		}

		if($this->sendBuffer !== null){
			$this->flushSendBuffer();
		}

		return false;
	}

	/**
	 * Returns a client-friendly gamemode of the specified real gamemode
	 * This function takes care of handling gamemodes known to MCPE (as of 1.1.0.3, that includes Survival, Creative and Adventure)
	 *
	 * @internal
	 * @param GameMode $gamemode
	 *
	 * @return int
	 */
	public static function getClientFriendlyGamemode(GameMode $gamemode) : int{
		if($gamemode === GameMode::SPECTATOR()){
			return GameMode::CREATIVE()->getMagicNumber();
		}

		return $gamemode->getMagicNumber();
	}
}
