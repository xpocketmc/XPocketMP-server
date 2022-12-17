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

namespace pocketmine\item;

use pocketmine\block\utils\DyeColor;
use pocketmine\color\Color;
use pocketmine\data\SavedDataLoadingException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\Binary;

class FireworkStar extends Item{

	protected const TAG_EXPLOSION = "FireworksItem"; //TAG_Compound
	protected const TAG_CUSTOM_COLOR = "customColor"; //TAG_Int

	protected FireworkRocketExplosion $explosion;

	protected ?Color $customColor = null;

	public function __construct(ItemIdentifier $identifier, string $name){
		parent::__construct($identifier, $name);

		$this->explosion = new FireworkRocketExplosion(FireworkRocketType::SMALL_SPHERE(), [DyeColor::BLACK()], [], false, false);
	}

	public function getExplosion() : FireworkRocketExplosion{
		return $this->explosion;
	}

	/** @return $this */
	public function setExplosion(FireworkRocketExplosion $explosion) : self{
		$this->explosion = $explosion;
		return $this;
	}

	/** @return $this */
	public function getColor() : Color{
		return $this->customColor ?? $this->explosion->getColorMix();
	}

	public function hasCustomColor() : bool{
		return $this->customColor !== null;
	}

	public function getCustomColor() : ?Color{
		$this->customColor;
	}

	/** @return $this */
	public function setCustomColor(Color $color) : self{
		$this->customColor = $color;
		return $this;
	}

	/** @return $this */
	public function clearCustomColor() : self{
		$this->customColor = null;
		return $this;
	}

	protected function deserializeCompoundTag(CompoundTag $tag) : void{
		parent::deserializeCompoundTag($tag);

		$explosionTag = $tag->getTag(self::TAG_EXPLOSION);
		if (!$explosionTag instanceof CompoundTag) {
			throw new SavedDataLoadingException("Missing explosion data");
		}
		$this->explosion = FireworkRocketExplosion::fromCompoundTag($explosionTag);

		$customColor = Color::fromARGB(Binary::unsignInt($tag->getInt(self::TAG_CUSTOM_COLOR)));
		$color = $this->explosion->getColorMix();
		if($customColor->getA() !== $color->getA() ||
			$customColor->getR() !== $color->getR() ||
			$customColor->getG() !== $color->getG() ||
			$customColor->getB() !== $color->getB()
		){ //check that $customColor is actually custom.
			$this->customColor = $customColor;
		}
	}

	protected function serializeCompoundTag(CompoundTag $tag) : void{
		parent::serializeCompoundTag($tag);

		$tag->setTag(self::TAG_EXPLOSION, $this->explosion->toCompoundTag());
		$tag->setInt(self::TAG_CUSTOM_COLOR, Binary::signInt($this->getColor()->toARGB()));
	}
}
