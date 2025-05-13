<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withImportNames(importShortClasses: false)
    ->withPhpSets()
    ->withPaths([
        __DIR__ . '/src',
    ])
    // uncomment to reach your current PHP version
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true
    )
    ->withTypeCoverageLevel(0)
;
