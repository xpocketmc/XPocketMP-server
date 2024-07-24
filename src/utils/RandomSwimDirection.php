<?php

declare(strict_types=1);

namespace pocketmine\entity\utils;

use pocketmine\math\Vector3;

class RandomSwimDirection {
    public static function generate() : Vector3 {
        $x = mt_rand(-100, 100) / 100;
        $y = mt_rand(-100, 100) / 100;
        $z = mt_rand(-100, 100) / 100;
        return new Vector3($x, $y, $z);
    }
}
