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
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

if(count($argv) !== 3){
	fwrite(STDERR, "required args: <git hash> <tag name>");
	exit(1);
}

echo json_encode([
	"php_version" => sprintf("%d.%d", PHP_MAJOR_VERSION, PHP_MINOR_VERSION),
	"base_version" => \pocketmine\BASE_VERSION,
	"build" => \pocketmine\BUILD_NUMBER,
	"is_dev" => \pocketmine\IS_DEVELOPMENT_BUILD,
	"git_commit" => $argv[1],
	"mcpe_version" => \pocketmine\network\mcpe\protocol\ProtocolInfo::MINECRAFT_VERSION_NETWORK,
	"date" => time(), //TODO: maybe we should embed this in VersionInfo?
	"details_url" => "https://github.com/pmmp/PocketMine-MP/releases/tag/$argv[2]/",
	"download_url" => "https://github.com/pmmp/PocketMine-MP/releases/download/$argv[2]/PocketMine-MP.phar",
	"source_url" => "https://github.com/pmmp/PocketMine-MP/tree/$argv[2]",
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
