<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
	__DIR__ . '/build',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/tools',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets(php84: true)
    ->withTypeCoverageLevel(3)
    ->withDeadCodeLevel(3)
    ->withCodeQualityLevel(3);
