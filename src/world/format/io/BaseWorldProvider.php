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

namespace pocketmine\world\format\io;

use pocketmine\data\bedrock\block\BlockStateDeserializeException;
use pocketmine\data\bedrock\block\BlockStateDeserializer;
use pocketmine\data\bedrock\block\BlockStateSerializer;
use pocketmine\data\bedrock\block\upgrade\BlockDataUpgrader;
use pocketmine\world\format\io\exception\CorruptedWorldException;
use pocketmine\world\format\io\exception\UnsupportedWorldFormatException;
use pocketmine\world\format\PalettedBlockArray;
use pocketmine\world\WorldException;
use function count;
use function file_exists;
use function implode;

abstract class BaseWorldProvider implements WorldProvider{
	protected WorldData $worldData;

	protected BlockStateDeserializer $blockStateDeserializer;
	protected BlockDataUpgrader $blockDataUpgrader;
	protected BlockStateSerializer $blockStateSerializer;

	public function __construct(
		protected string $path,
		protected \Logger $logger
	){
		if(!file_exists($path)){
			throw new WorldException("World does not exist");
		}

		//TODO: this should not rely on singletons
		$this->blockStateDeserializer = GlobalBlockStateHandlers::getDeserializer();
		$this->blockDataUpgrader = GlobalBlockStateHandlers::getUpgrader();
		$this->blockStateSerializer = GlobalBlockStateHandlers::getSerializer();

		$this->worldData = $this->loadLevelData();
	}

	/**
	 * @throws CorruptedWorldException
	 * @throws UnsupportedWorldFormatException
	 */
	abstract protected function loadLevelData() : WorldData;

 	private function translatePalette(PalettedBlockArray $blockArray, \Logger $logger) : PalettedBlockArray{
        /** @phpstan-var list<int> $palette */
        $palette = $blockArray->getPalette();

        $newPalette = [];
        $blockDecodeErrors = [];
        $unknownBlocks = [];

        foreach($palette as $k => $legacyIdMeta){
            $id = $legacyIdMeta >> 4;
            $meta = $legacyIdMeta & 0xf;

            if (isset($unknownBlocks[$id][$meta])) {
                $newPalette[$k] = $unknownBlocks[$id][$meta];
                continue;
            }

            try{
                $newStateData = $this->blockDataUpgrader->upgradeIntIdMeta($id, $meta);
            }catch(BlockStateDeserializeException $e){
                $blockDecodeErrors[] = "Palette offset $k / Failed to upgrade legacy ID/meta $id:$meta: " . $e->getMessage();
                $newStateData = GlobalBlockStateHandlers::getUnknownBlockStateData();
                $unknownBlocks[$id][$meta] = $newStateData;
            }

            try{
                $newPalette[$k] = $this->blockStateDeserializer->deserialize($newStateData);
            }catch(BlockStateDeserializeException $e){
                $blockDecodeErrors[] = "Palette offset $k / Failed to deserialize upgraded state $id:$meta: " . $e->getMessage();
            $newPalette[$k] = $this->blockStateDeserializer->deserialize(GlobalBlockStateHandlers::getUnknownBlockStateData());
            }
        }

        if(count($blockDecodeErrors) > 0){
            $logger->error("Errors decoding/upgrading blocks:\n - " . implode("\n - ", $blockDecodeErrors));
        }

        return PalettedBlockArray::fromData(
            $blockArray->getBitsPerBlock(),
            $blockArray->getWordArray(),
            $newPalette
        );
	}

	protected function palettizeLegacySubChunkXZY(string $idArray, string $metaArray, \Logger $logger) : PalettedBlockArray{
		return $this->translatePalette(SubChunkConverter::convertSubChunkXZY($idArray, $metaArray), $logger);
	}

	protected function palettizeLegacySubChunkYZX(string $idArray, string $metaArray, \Logger $logger) : PalettedBlockArray{
		return $this->translatePalette(SubChunkConverter::convertSubChunkYZX($idArray, $metaArray), $logger);
	}

	protected function palettizeLegacySubChunkFromColumn(string $idArray, string $metaArray, int $yOffset, \Logger $logger) : PalettedBlockArray{
		return $this->translatePalette(SubChunkConverter::convertSubChunkFromLegacyColumn($idArray, $metaArray, $yOffset), $logger);
	}

	public function getPath() : string{
		return $this->path;
	}

	public function getWorldData() : WorldData{
		return $this->worldData;
	}
}
