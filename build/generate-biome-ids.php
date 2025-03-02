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

namespace pocketmine\build\generate_biome_ids;

use pocketmine\data\bedrock\BedrockDataFiles;
use pocketmine\utils\Filesystem;
use pocketmine\utils\Utils;
use function asort;
use function dirname;
use function fclose;
use function fopen;
use function fwrite;
use function is_array;
use function is_int;
use function is_string;
use function json_decode;
use function str_replace;
use function strtoupper;
use const SORT_NUMERIC;

require dirname(__DIR__) . '/vendor/autoload.php';

const HEADER = <<<'HEADER'
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


HEADER;

/** @return resource */
function safe_fopen(string $file, string $flags){
	$result = fopen($file, $flags);
	if($result === false){
		throw new \RuntimeException("Failed to open file");
	}
	return $result;
}

function make_const_name(string $name) : string{
	return strtoupper(str_replace(['.', 'minecraft:'], ['_', ''], $name));
}

/**
 * @param int[] $map
 * @phpstan-param array<string, int> $map
 */
function generate(array $map, string $outputFile) : void{
	$file = safe_fopen($outputFile, 'wb');
	fwrite($file, HEADER);
	fwrite($file, <<<'CLASSHEADER'
namespace pocketmine\data\bedrock;

final class BiomeIds{

	private function __construct(){
		//NOOP
	}


CLASSHEADER
);
	$list = $map;
	asort($list, SORT_NUMERIC);
	$lastId = -1;
	foreach(Utils::stringifyKeys($list) as $name => $id){
		if($name === ""){
			continue;
		}
		if($id !== $lastId + 1){
			fwrite($file, "\n");
		}
		$lastId = $id;
		fwrite($file, "\tpublic const " . make_const_name($name) . ' = ' . $id . ';' . "\n");
	}
	fwrite($file, "}\n");
	fclose($file);
}

$ids = json_decode(Filesystem::fileGetContents(BedrockDataFiles::BIOME_ID_MAP_JSON), true);
if(!is_array($ids)){
	throw new \RuntimeException("Invalid biome ID map, expected array for root JSON object");
}
$cleanedIds = [];
foreach($ids as $name => $id){
	if(!is_string($name) || !is_int($id)){
		throw new \RuntimeException("Invalid biome ID map, expected string => int map");
	}
	$cleanedIds[$name] = $id;
}
generate($cleanedIds, dirname(__DIR__) . '/src/data/bedrock/BiomeIds.php');

echo "Done. Don't forget to run CS fixup after generating code.\n";
