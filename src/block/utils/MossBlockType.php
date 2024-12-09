<?php

namespace pocketmine\block\utils;

use pocketmine\utils\LegacyEnumShimTrait;

enum MossBlockType{
	use LegacyEnumShimTrait;

	case NORMAL;
	case PALE;
}
