<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;

interface DataNormalizerInterface
{
    public function buildData(Data $data, $object, ?string $format = null, array $context = []);

    public static function getSupportedTypes(): array;

    public function getSerializationGroups(array $groups, array $context = []): ?array;

    public function isStopped(): bool;
}
