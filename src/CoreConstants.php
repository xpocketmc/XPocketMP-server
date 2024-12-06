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
 * @author XPocketMP Team
 * @link http://www.xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace XPocketMP;

use function define;
use function defined;
use function dirname;

// composer autoload doesn't use require_once and also pthreads can inherit things
if(defined('XPocketMP\_CORE_CONSTANTS_INCLUDED')){
	return;
}
define('XPocketMP\_CORE_CONSTANTS_INCLUDED', true);

define('XPocketMP\PATH', dirname(__DIR__) . '/');
define('XPocketMP\RESOURCE_PATH', dirname(__DIR__) . '/resources/');
define('XPocketMP\BEDROCK_DATA_PATH', dirname(__DIR__) . '/vendor/XPocketMP/bedrock-data/');
define('XPocketMP\LOCALE_DATA_PATH', dirname(__DIR__) . '/vendor/XPocketMP/locale-data/');
define('XPocketMP\BEDROCK_BLOCK_UPGRADE_SCHEMA_PATH', dirname(__DIR__) . '/vendor/XPocketMP/bedrock-block-upgrade-schema/');
define('XPocketMP\BEDROCK_ITEM_UPGRADE_SCHEMA_PATH', dirname(__DIR__) . '/vendor/XPocketMP/bedrock-item-upgrade-schema/');
define('XPocketMP\COMPOSER_AUTOLOADER_PATH', dirname(__DIR__) . '/vendor/autoload.php');