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

namespace pocketmine\plugin;

use function spl_object_id;

/**
 * @phpstan-import-type LoggerAttachment from \AttachableLogger
 */
class PluginLogger extends \PrefixedLogger implements \AttachableLogger{

	/**
	 * @var \Closure[]
	 * @phpstan-var LoggerAttachment[]
	 */
	private array $attachments = [];

	/**
	 * @phpstan-param LoggerAttachment $attachment
	 */
	public function addAttachment(\Closure $attachment){
		$this->attachments[spl_object_id($attachment)] = $attachment;
	}

	/**
	 * @phpstan-param LoggerAttachment $attachment
	 */
	public function removeAttachment(\Closure $attachment){
		unset($this->attachments[spl_object_id($attachment)]);
	}

	public function removeAttachments(){
		$this->attachments = [];
	}

	public function getAttachments(){
		return $this->attachments;
	}

	public function log($level, $message){
		parent::log($level, $message);
		foreach($this->attachments as $attachment){
			$attachment($level, $message);
		}
	}
}
