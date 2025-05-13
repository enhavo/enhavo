<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPhpVersion(PhpVersion::PHP_83)
    ->withImportNames(importShortClasses: false)
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withTypeCoverageLevel(0)
;
