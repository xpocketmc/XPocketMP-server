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

namespace pocketmine\network\mcpe;

use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\serializer\PacketBatch;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\Server;
use pocketmine\profiler\Timings;
use pocketmine\utils\BinaryStream;
use function count;
use function log;
use function spl_object_id;
use function strlen;

final class StandardPacketBroadcaster implements PacketBroadcaster{
	public function __construct(
		private Server $server
	){}

	public function broadcastPackets(array $recipients, array $packets) : void{
		//TODO: this shouldn't really be called here, since the broadcaster might be replaced by an alternative
		//implementation that doesn't fire events
		if(DataPacketSendEvent::hasHandlers()){
			$ev = new DataPacketSendEvent($recipients, $packets);
			$ev->call();
			if($ev->isCancelled()){
				return;
			}
			$packets = $ev->getPackets();
		}

		$compressors = [];

		$targetsByCompressor = [];
		foreach($recipients as $recipient){
			//TODO: different compressors might be compatible, it might not be necessary to split them up by object
			$compressor = $recipient->getCompressor();
			$compressors[spl_object_id($compressor)] = $compressor;

			$targetsByCompressor[spl_object_id($compressor)][] = $recipient;
		}

		$totalLength = 0;
		$packetBuffers = [];
		foreach($packets as $packet){
			$buffer = NetworkSession::encodePacketTimed(PacketSerializer::encoder(), $packet);
			//varint length prefix + packet buffer
			$totalLength += (((int) log(strlen($buffer), 128)) + 1) + strlen($buffer);
			$packetBuffers[] = $buffer;
		}

		foreach($targetsByCompressor as $compressorId => $compressorTargets){
			$compressor = $compressors[$compressorId];

			$threshold = $compressor->getCompressionThreshold();
			if(count($compressorTargets) > 1 && $threshold !== null && $totalLength >= $threshold){
				//do not prepare shared batch unless we're sure it will be compressed
				$stream = new BinaryStream();
				PacketBatch::encodeRaw($stream, $packetBuffers);
				$batchBuffer = $stream->getBuffer();

				$batch = $this->server->prepareBatch($batchBuffer, $compressor, timings: Timings::$playerNetworkSendCompressBroadcast);
				foreach($compressorTargets as $target){
					$target->queueCompressed($batch);
				}
			}else{
				foreach($compressorTargets as $target){
					foreach($packetBuffers as $packetBuffer){
						$target->addToSendBuffer($packetBuffer);
					}
				}
			}
		}
	}
}
