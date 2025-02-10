<?php

declare(strict_types=1);

namespace pocketmine\world\generator;

use pocketmine\block\VanillaBlocks;
use pocketmine\world\chunk\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\SubChunk;
use pocketmine\world\generator\object\OreType;
use pocketmine\world\generator\populator\Ore;
use pocketmine\world\generator\populator\Populator;
use function count;

class Flat extends Generator {
    private Chunk $chunk;
	/** @var Populator[] */
    private array $populators = [];

    /**
     * @throws InvalidGeneratorOptionsException
     */
    public function __construct(int $seed, string $preset) {
        parent::__construct($seed, $preset !== "" ? $preset : "3;bedrock,5xdirt,grass;1;");

        $ores = new Ore();
        $stone = VanillaBlocks::STONE();
        $ores->setOreTypes([
            new OreType(VanillaBlocks::COAL_ORE(), $stone, 10, 16, 1, 30),
            new OreType(VanillaBlocks::IRON_ORE(), $stone, 8, 8, 1, 25),
            new OreType(VanillaBlocks::REDSTONE_ORE(), $stone, 3, 7, 1, 15),
            new OreType(VanillaBlocks::LAPIS_LAZULI_ORE(), $stone, 1, 6, 1, 15),
            new OreType(VanillaBlocks::GOLD_ORE(), $stone, 2, 8, 1, 20),
            new OreType(VanillaBlocks::DIAMOND_ORE(), $stone, 1, 7, 1, 10)
        ]);
        $this->populators[] = $ores;

        $this->generateBaseChunk();
    }

    protected function generateBaseChunk(): void {
        $this->chunk = new Chunk([], false);

        // Struktur tanah mirip dunia Flat Vanilla
        $structure = [
            VanillaBlocks::BEDROCK()->getStateId(),
            VanillaBlocks::DIRT()->getStateId(),
            VanillaBlocks::DIRT()->getStateId(),
            VanillaBlocks::DIRT()->getStateId(),
            VanillaBlocks::DIRT()->getStateId(),
            VanillaBlocks::DIRT()->getStateId(),
            VanillaBlocks::GRASS()->getStateId()
        ];

        $count = count($structure);
        for ($sy = 0; $sy < $count; $sy += SubChunk::EDGE_LENGTH) {
            $subchunk = $this->chunk->getSubChunk($sy >> SubChunk::COORD_BIT_SIZE);
            for ($y = 0; $y < SubChunk::EDGE_LENGTH && isset($structure[$y | $sy]); ++$y) {
                $id = $structure[$y | $sy];

                for ($Z = 0; $Z < SubChunk::EDGE_LENGTH; ++$Z) {
                    for ($X = 0; $X < SubChunk::EDGE_LENGTH; ++$X) {
                        $subchunk->setBlockStateId($X, $y, $Z, $id);
                    }
                }
            }
        }
    }

    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        $world->setChunk($chunkX, $chunkZ, clone $this->chunk);
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        $this->random->setSeed($this->seed ^ ($chunkX << 8) ^ $chunkZ);
        foreach ($this->populators as $populator) {
            $populator->populate($world, $chunkX, $chunkZ, $this->random);
        }
    }
}
