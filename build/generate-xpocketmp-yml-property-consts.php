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

use pocketmine\utils\Filesystem;
use pocketmine\utils\Utils;

require dirname(__DIR__) . '/vendor/autoload.php';

$defaultConfig = yaml_parse(Filesystem::fileGetContents(dirname(__DIR__) . '/resources/xpocketmp.yml'));

if(!is_array($defaultConfig)){
	fwrite(STDERR, "Invalid default xpocketmp.yml\n");
	exit(1);
}

$constants = [];

/**
 * @param mixed[]  $properties
 * @param string[] $constants
 * @phpstan-param array<string, string> $constants
 * @phpstan-param-out array<string, string> $constants
 */
function collectProperties(string $prefix, array $properties, array &$constants) : void{
	foreach($properties as $propertyName => $property){
		$fullPropertyName = ($prefix !== "" ? $prefix . "." : "") . $propertyName;

		$constName = str_replace([".", "-"], "_", strtoupper($fullPropertyName));
		$constants[$constName] = $fullPropertyName;

		if(is_array($property)){
			collectProperties($fullPropertyName, $property, $constants);
		}
	}
}

collectProperties("", $defaultConfig, $constants);
ksort($constants, SORT_STRING);

$file = fopen(dirname(__DIR__) . '/src/YmlServerProperties.php', 'wb');
if($file === false){
	fwrite(STDERR, "Failed to open output file\n");
	exit(1);
}
fwrite($file, "<?php\n");
fwrite($file, <<<'HEADER'

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


HEADER
);
fwrite($file, "declare(strict_types=1);\n\n");
fwrite($file, "namespace pocketmine;\n\n");

fwrite($file, <<<'DOC'
/**
 * @internal
 * Constants for all properties available in pocketmine.yml.
 * This is generated by build/generate-pocketmine-yml-property-consts.php.
 * Do not edit this file manually.
 */

DOC
);
fwrite($file, "final class YmlServerProperties{\n");
fwrite($file, <<<'CONSTRUCTOR'

	private function __construct(){
		//NOOP
	}


CONSTRUCTOR
);
foreach(Utils::stringifyKeys($constants) as $constName => $propertyName){
	fwrite($file, "\tpublic const $constName = '$propertyName';\n");
}
fwrite($file, "}\n");

fclose($file);

echo "Done. Don't forget to run CS fixup after generating code.\n";
