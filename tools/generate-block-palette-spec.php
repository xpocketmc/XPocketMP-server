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

namespace pocketmine\tools\generate_block_palette_spec;

use pocketmine\nbt\NbtException;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\BlockStateDictionary;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\Filesystem;
use pocketmine\utils\Utils;
use function array_values;
use function count;
use function dirname;
use function file_put_contents;
use function fwrite;
use function json_encode;
use function ksort;
use const JSON_PRETTY_PRINT;
use const SORT_NATURAL;
use const SORT_STRING;
use const STDERR;

require dirname(__DIR__) . '/vendor/autoload.php';

if(count($argv) !== 3){
	fwrite(STDERR, "Required arguments: input palette file path, output JSON file path\n");
	exit(1);
}

[, $inputFile, $outputFile] = $argv;

try{
	$states = BlockStateDictionary::loadPaletteFromString(Filesystem::fileGetContents($inputFile));
}catch(NbtException){
	fwrite(STDERR, "Invalid block palette file $argv[1]\n");
	exit(1);
}

$reportMap = [];

foreach($states as $state){
	$name = $state->getName();
	$reportMap[$name] ??= [];
	foreach(Utils::stringifyKeys($state->getStates()) as $propertyName => $value){
		if($value instanceof IntTag || $value instanceof StringTag){
			$rawValue = $value->getValue();
		}elseif($value instanceof ByteTag){
			$rawValue = match($value->getValue()){
				0 => false,
				1 => true,
				default => throw new AssumptionFailedError("Unexpected ByteTag value for $name -> $propertyName ($value)")
			};
		}else{
			throw new AssumptionFailedError("Unexpected tag type for $name -> $propertyName ($value)");
		}
		$reportMap[$name][$propertyName][$value::class . ":" . $value->getValue()] = $rawValue;
	}
}

foreach(Utils::stringifyKeys($reportMap) as $blockName => $propertyList){
	foreach(Utils::stringifyKeys($propertyList) as $propertyName => $propertyValues){
		ksort($propertyValues, SORT_NATURAL);
		$reportMap[$blockName][$propertyName] = array_values($propertyValues);
	}
}
ksort($reportMap, SORT_STRING);

file_put_contents($outputFile, json_encode($reportMap, JSON_PRETTY_PRINT));
