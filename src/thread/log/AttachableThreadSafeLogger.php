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

namespace pocketmine\thread\log;

use pmmp\thread\ThreadSafeArray;

abstract class AttachableThreadSafeLogger extends ThreadSafeLogger{

	/**
	 * @var ThreadSafeArray|ThreadSafeLoggerAttachment[]
	 * @phpstan-var ThreadSafeArray<int, ThreadSafeLoggerAttachment>
	 */
	protected ThreadSafeArray $attachments;

	public function __construct(){
		$this->attachments = new ThreadSafeArray();
	}

	public function addAttachment(ThreadSafeLoggerAttachment $attachment) : void{
		$this->attachments[] = $attachment;
	}

	public function removeAttachment(ThreadSafeLoggerAttachment $attachment) : void{
		foreach($this->attachments as $i => $a){
			if($attachment === $a){
				unset($this->attachments[$i]);
			}
		}
	}

	public function removeAttachments() : void{
		foreach($this->attachments as $i => $a){
			unset($this->attachments[$i]);
		}
	}

	/**
	 * @return ThreadSafeLoggerAttachment[]
	 */
	public function getAttachments() : array{
		return (array) $this->attachments;
	}
}
