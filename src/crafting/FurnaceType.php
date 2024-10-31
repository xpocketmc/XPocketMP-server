<?php

declare(strict_types=1);

namespace pocketmine\crafting;

use pocketmine\utils\LegacyEnumShimTrait;
use pocketmine\world\sound\BlastFurnaceSound;
use pocketmine\world\sound\CampfireSound;
use pocketmine\world\sound\FurnaceSound;
use pocketmine\world\sound\SmokerSound;
use pocketmine\world\sound\Sound;
use function spl_object_id;

/**
 * @method static FurnaceType BLAST_FURNACE()
 * @method static FurnaceType FURNACE()
 * @method static FurnaceType SMOKER()
 * @method static FurnaceType CAMPFIRE()
 * @method static FurnaceType SOUL_CAMPFIRE()
 *
 * @phpstan-type TMetadata array{0: int, 1: Sound}
 */
enum FurnaceType {
    use LegacyEnumShimTrait;

    case FURNACE;
    case BLAST_FURNACE;
    case SMOKER;
    case CAMPFIRE;
    case SOUL_CAMPFIRE;

    /**
     * @phpstan-return TMetadata
     */
    private function getMetadata(): array {
        static $cache = [];

        return $cache[spl_object_id($this)] ??= match($this) {
            self::FURNACE => [200, new FurnaceSound()],
            self::BLAST_FURNACE => [100, new BlastFurnaceSound()],
            self::SMOKER => [100, new SmokerSound()],
            self::CAMPFIRE => [100, new CampfireSound()],
            self::SOUL_CAMPFIRE => [100, new CampfireSound()], // Tambahkan sound yang lebih tepat jika tersedia
        };
    }

    public function getCookDurationTicks(): int {
        return $this->getMetadata()[0];
    }

    public function getCookSound(): Sound {
        return $this->getMetadata()[1];
    }
}
