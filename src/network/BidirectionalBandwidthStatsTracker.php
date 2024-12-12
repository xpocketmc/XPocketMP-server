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

namespace pocketmine\network;

final class BidirectionalBandwidthStatsTracker{
	private BandwidthStatsTracker $send;
	private BandwidthStatsTracker $receive;

	/** @phpstan-param positive-int $historySize */
	public function __construct(int $historySize){
		$this->send = new BandwidthStatsTracker($historySize);
		$this->receive = new BandwidthStatsTracker($historySize);
	}

	public function getSend() : BandwidthStatsTracker{ return $this->send; }

	public function getReceive() : BandwidthStatsTracker{ return $this->receive; }

	public function add(int $sendBytes, int $recvBytes) : void{
		$this->send->add($sendBytes);
		$this->receive->add($recvBytes);
	}

	/** @see BandwidthStatsTracker::rotateHistory() */
	public function rotateAverageHistory() : void{
		$this->send->rotateHistory();
		$this->receive->rotateHistory();
	}

	/** @see BandwidthStatsTracker::resetHistory() */
	public function resetHistory() : void{
		$this->send->resetHistory();
		$this->receive->resetHistory();
	}
}
