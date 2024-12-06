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
 * @author XPocketMP Team
 * @link http://www.xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace XPocketMP\entity\projectile;

use XPocketMPlock\BlockTypeTags;
use XPocketMPlock\VanillaBlocks;
use XPocketMP\color\Color;
use XPocketMP\data\bedrock\PotionTypeIdMap;
use XPocketMP\entity\effect\EffectInstance;
use XPocketMP\entity\effect\InstantEffect;
use XPocketMP\entity\Entity;
use XPocketMP\entity\Living;
use XPocketMP\entity\Location;
use XPocketMP\event\entity\ProjectileHitBlockEvent;
use XPocketMP\event\entity\ProjectileHitEntityEvent;
use XPocketMP\event\entity\ProjectileHitEvent;
use XPocketMP\item\Potion;
use XPocketMP\item\PotionType;
use XPocketMP\nbt\tag\CompoundTag;
use XPocketMP\network\mcpe\protocol\types\entity\EntityIds;
use XPocketMP\network\mcpe\protocol\types\entity\EntityMetadataCollection;
use XPocketMP\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use XPocketMP\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use XPocketMP\world\particle\PotionSplashParticle;
use XPocketMP\world\sound\PotionSplashSound;
use function count;
use function round;
use function sqrt;

class SplashPotion extends Throwable{

	public const TAG_POTION_ID = "PotionId"; //TAG_Short

	public static function getNetworkTypeId() : string{ return EntityIds::SPLASH_POTION; }

	protected bool $linger = false;
	protected PotionType $potionType;

	public function __construct(Location $location, ?Entity $shootingEntity, PotionType $potionType, ?CompoundTag $nbt = null){
		$this->potionType = $potionType;
		parent::__construct($location, $shootingEntity, $nbt);
	}

	protected function getInitialGravity() : float{ return 0.05; }

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();
		$nbt->setShort(self::TAG_POTION_ID, PotionTypeIdMap::getInstance()->toId($this->getPotionType()));

		return $nbt;
	}

	public function getResultDamage() : int{
		return -1; //no damage
	}

	protected function onHit(ProjectileHitEvent $event) : void{
		$effects = $this->getPotionEffects();
		$hasEffects = true;

		if(count($effects) === 0){
			$particle = new PotionSplashParticle(PotionSplashParticle::DEFAULT_COLOR());
			$hasEffects = false;
		}else{
			$colors = [];
			foreach($effects as $effect){
				$level = $effect->getEffectLevel();
				for($j = 0; $j < $level; ++$j){
					$colors[] = $effect->getColor();
				}
			}
			$particle = new PotionSplashParticle(Color::mix(...$colors));
		}

		$this->getWorld()->addParticle($this->location, $particle);
		$this->broadcastSound(new PotionSplashSound());

		if($hasEffects){
			if(!$this->willLinger()){
				foreach($this->getWorld()->getCollidingEntities($this->boundingBox->expandedCopy(4.125, 2.125, 4.125), $this) as $entity){
					if($entity instanceof Living){
						$distanceSquared = $entity->getEyePos()->distanceSquared($this->location);
						if($distanceSquared > 16){ //4 blocks
							continue;
						}

						$distanceMultiplier = 1 - (sqrt($distanceSquared) / 4);
						if($event instanceof ProjectileHitEntityEvent && $entity === $event->getEntityHit()){
							$distanceMultiplier = 1.0;
						}

						foreach($this->getPotionEffects() as $effect){
							//getPotionEffects() is used to get COPIES to avoid accidentally modifying the same effect instance already applied to another entity

							if(!($effect->getType() instanceof InstantEffect)){
								$newDuration = (int) round($effect->getDuration() * 0.75 * $distanceMultiplier);
								if($newDuration < 20){
									continue;
								}
								$effect->setDuration($newDuration);
								$entity->getEffects()->add($effect);
							}else{
								$effect->getType()->applyEffect($entity, $effect, $distanceMultiplier, $this);
							}
						}
					}
				}
			}else{
				//TODO: lingering potions
			}
		}elseif($event instanceof ProjectileHitBlockEvent && $this->getPotionType() === PotionType::WATER){
			$blockIn = $event->getBlockHit()->getSide($event->getRayTraceResult()->getHitFace());

			if($blockIn->hasTypeTag(BlockTypeTags::FIRE)){
				$this->getWorld()->setBlock($blockIn->getPosition(), VanillaBlocks::AIR());
			}
			foreach($blockIn->getHorizontalSides() as $horizontalSide){
				if($horizontalSide->hasTypeTag(BlockTypeTags::FIRE)){
					$this->getWorld()->setBlock($horizontalSide->getPosition(), VanillaBlocks::AIR());
				}
			}
		}
	}

	/**
	 * Returns the meta value of the potion item that this splash potion corresponds to. This decides what effects will be applied to the entity when it collides with its target.
	 */
	public function getPotionType() : PotionType{
		return $this->potionType;
	}

	public function setPotionType(PotionType $type) : void{
		$this->potionType = $type;
		$this->networkPropertiesDirty = true;
	}

	/**
	 * Returns whether this splash potion will create an area-effect cloud when it lands.
	 */
	public function willLinger() : bool{
		return $this->linger;
	}

	/**
	 * Sets whether this splash potion will create an area-effect-cloud when it lands.
	 */
	public function setLinger(bool $value = true) : void{
		$this->linger = $value;
		$this->networkPropertiesDirty = true;
	}

	/**
	 * @return EffectInstance[]
	 */
	public function getPotionEffects() : array{
		return $this->potionType->getEffects();
	}

	protected function syncNetworkData(EntityMetadataCollection $properties) : void{
		parent::syncNetworkData($properties);

		$properties->setShort(EntityMetadataProperties::POTION_AUX_VALUE, PotionTypeIdMap::getInstance()->toId($this->potionType));
		$properties->setGenericFlag(EntityMetadataFlags::LINGER, $this->linger);
	}
}