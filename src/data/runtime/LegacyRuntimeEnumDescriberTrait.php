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

namespace XPocketMP\data\runtime;

/**
 * Provides backwards-compatible shims for the old codegen'd enum describer methods.
 * This is kept for plugin backwards compatibility, but these functions should not be used in new code.
 * @deprecated
 */
trait LegacyRuntimeEnumDescriberTrait{
	abstract protected function enum(\UnitEnum &$case) : void;

	public function bellAttachmentType(\XPocketMPlock\utils\BellAttachmentType &$value) : void{
		$this->enum($value);
	}

	public function copperOxidation(\XPocketMPlock\utils\CopperOxidation &$value) : void{
		$this->enum($value);
	}

	public function coralType(\XPocketMPlock\utils\CoralType &$value) : void{
		$this->enum($value);
	}

	public function dirtType(\XPocketMPlock\utils\DirtType &$value) : void{
		$this->enum($value);
	}

	public function dripleafState(\XPocketMPlock\utils\DripleafState &$value) : void{
		$this->enum($value);
	}

	public function dyeColor(\XPocketMPlock\utils\DyeColor &$value) : void{
		$this->enum($value);
	}

	public function froglightType(\XPocketMPlock\utils\FroglightType &$value) : void{
		$this->enum($value);
	}

	public function leverFacing(\XPocketMPlock\utils\LeverFacing &$value) : void{
		$this->enum($value);
	}

	public function medicineType(\XPocketMP\item\MedicineType &$value) : void{
		$this->enum($value);
	}

	public function mobHeadType(\XPocketMPlock\utils\MobHeadType &$value) : void{
		$this->enum($value);
	}

	public function mushroomBlockType(\XPocketMPlock\utils\MushroomBlockType &$value) : void{
		$this->enum($value);
	}

	public function potionType(\XPocketMP\item\PotionType &$value) : void{
		$this->enum($value);
	}

	public function slabType(\XPocketMPlock\utils\SlabType &$value) : void{
		$this->enum($value);
	}

	public function suspiciousStewType(\XPocketMP\item\SuspiciousStewType &$value) : void{
		$this->enum($value);
	}
}