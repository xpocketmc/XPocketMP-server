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

namespace pocketmine\event;

use pocketmine\plugin\Plugin;
use pocketmine\utils\Utils;

class HandlerListManager{

	/** @var HandlerListManager */
	private static $globalInstance = null;

	public static function global() : self{
		return self::$globalInstance ?? (self::$globalInstance = new self);
	}

	/**
	 * @var HandlerList[] classname => HandlerList
	 */
	private $allLists = [];

	/**
	 * Unregisters all the listeners
	 * If a Plugin or Listener is passed, all the listeners with that object will be removed
	 *
	 * @param Plugin|Listener|null $object
	 */
	public function unregisterAll($object = null) : void{
		if($object instanceof Listener or $object instanceof Plugin){
			foreach($this->allLists as $h){
				$h->unregister($object);
			}
		}else{
			foreach($this->allLists as $h){
				$h->clear();
			}
		}
	}

	/**
	 * @param ReflectionClass $class
	 * @phpstan-param \ReflectionClass<Event> $class
	 *
	 * @return bool
	 */
	private static function isValidClass(\ReflectionClass $class) : bool{
		$tags = Utils::parseDocComment((string) $class->getDocComment());
		return !$class->isAbstract() || isset($tags["allowHandle"]);
	}

	/**
	 * @param \ReflectionClass $class
	 * @phpstan-param \ReflectionClass<Event> $class
	 *
	 * @return \ReflectionClass|null
	 * @phpstan-return \ReflectionClass<Event>|null
	 */
	private static function resolveNearestHandleableParent(\ReflectionClass $class) : ?\ReflectionClass{
		for($parent = $class->getParentClass(); $parent !== false && !self::isValidClass($parent); $parent = $parent->getParentClass()){
			//NOOP
		}
		return $parent ?: null;
	}

	/**
	 * Returns the HandlerList for listeners that explicitly handle this event.
	 *
	 * Calling this method also lazily initializes the $classMap inheritance tree of handler lists.
	 *
	 * @param string $event
	 *
	 * @return HandlerList
	 * @throws \ReflectionException
	 * @throws \InvalidArgumentException
	 */
	public function getListFor(string $event) : HandlerList{
		if(isset($this->allLists[$event])){
			return $this->allLists[$event];
		}

		$class = new \ReflectionClass($event);
		if(!self::isValidClass($class)){
			throw new \InvalidArgumentException("Event must be non-abstract or have the @allowHandle annotation");
		}

		$parent = self::resolveNearestHandleableParent($class);
		return $this->allLists[$event] = new HandlerList($event, $parent !== null ? $this->getListFor($parent->getName()) : null);
	}

	/**
	 * @return HandlerList[]
	 */
	public function getAll() : array{
		return $this->allLists;
	}
}
