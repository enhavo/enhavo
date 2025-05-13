<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Metadata;

interface DriverInterface
{
    /** Get all FQCN as array */
    public function getAllClasses(): array;

    /** Get normalized data of a class or null, and if no data exists false */
    public function loadClass($className): array|false|null;
}
