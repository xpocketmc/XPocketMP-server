<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/tools',
    ])

    ->withPhpSets(804)
    ->withTypeCoverageLevel(3)
    ->withDeadCodeLevel(3)
    ->withCodeQualityLevel(3);
