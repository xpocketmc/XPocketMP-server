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

namespace pocketmine\build\generate_bedrockdata_path_consts;

use Symfony\Component\Filesystem\Path;
use function dirname;
use function fclose;
use function fopen;
use function fwrite;
use function is_dir;
use function is_file;
use function scandir;
use function str_replace;
use function strtoupper;
use const PHP_EOL;
use const pocketmine\BEDROCK_DATA_PATH;
use const SCANDIR_SORT_ASCENDING;
use const STDERR;

require dirname(__DIR__) . '/vendor/autoload.php';

function constantify(string $permissionName) : string{
	return strtoupper(str_replace([".", "-"], "_", $permissionName));
}

$files = scandir(BEDROCK_DATA_PATH, SCANDIR_SORT_ASCENDING);
if($files === false){
	fwrite(STDERR, "Couldn't find any files in " . BEDROCK_DATA_PATH . PHP_EOL);
	exit(1);
}

$consts = [];

foreach($files as $file){
	if($file === '.' || $file === '..'){
		continue;
	}
	if($file[0] === '.'){
		continue;
	}
	$path = Path::join(BEDROCK_DATA_PATH, $file);
	if(!is_file($path) && !is_dir($path)){
		continue;
	}

	foreach([
		'README.md',
		'LICENSE',
		'composer.json',
		'.github',
	] as $ignored){
		if($file === $ignored){
			continue 2;
		}
	}

	$consts[] = $file;
}

$output = fopen(dirname(__DIR__) . '/src/data/bedrock/BedrockDataFiles.php', 'wb');
if($output === false){
	fwrite(STDERR, "Couldn't open output file" . PHP_EOL);
	exit(1);
}
fwrite($output, <<<'HEADER'
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

namespace pocketmine\data\bedrock;

use const pocketmine\BEDROCK_DATA_PATH;

final class BedrockDataFiles{
	private function __construct(){
		//NOOP
	}


HEADER
);

foreach($consts as $constName => $fileName){
	fwrite($output, "\tpublic const " . constantify($fileName) . " = BEDROCK_DATA_PATH . '/$fileName';\n");
}

fwrite($output, "}\n");
fclose($output);

echo "Done. Don't forget to run CS fixup after generating code.\n";
