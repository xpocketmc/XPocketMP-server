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

namespace pocketmine\network\mcpe\compression;

use function array_push;

class CompressBatchPromise{
	/**
	 * @var \Closure[]
	 * @phpstan-var (\Closure(self) : void)[]
	 */
	private array $callbacks = [];

	private ?string $result = null;

	private bool $cancelled = false;

	/**
	 * @phpstan-param \Closure(self) : void ...$callbacks
	 */
	public function onResolve(\Closure ...$callbacks) : void{
		$this->checkCancelled();
		if($this->result !== null){
			foreach($callbacks as $callback){
				$callback($this);
			}
		}else{
			array_push($this->callbacks, ...$callbacks);
		}
	}

	public function resolve(string $result) : void{
		if(!$this->cancelled){
			if($this->result !== null){
				throw new \LogicException("Cannot resolve promise more than once");
			}
			$this->result = $result;
			foreach($this->callbacks as $callback){
				$callback($this);
			}
			$this->callbacks = [];
		}
	}

	/**
	 * @return \Closure[]
	 * @phpstan-return (\Closure(self) : void)[]
	 */
	public function getResolveCallbacks() : array{
		return $this->callbacks;
	}

	public function getResult() : string{
		$this->checkCancelled();
		if($this->result === null){
			throw new \LogicException("Promise has not yet been resolved");
		}
		return $this->result;
	}

	public function hasResult() : bool{
		return $this->result !== null;
	}

	public function isCancelled() : bool{
		return $this->cancelled;
	}

	public function cancel() : void{
		if($this->hasResult()){
			throw new \LogicException("Cannot cancel a resolved promise");
		}
		$this->cancelled = true;
	}

	private function checkCancelled() : void{
		if($this->cancelled){
			throw new \InvalidArgumentException("Promise has been cancelled");
		}
	}
}
