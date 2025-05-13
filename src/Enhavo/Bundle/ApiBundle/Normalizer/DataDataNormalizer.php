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

class DataDataNormalizer extends AbstractDataNormalizer
{
    public function buildData(Data $data, $object, ?string $format = null, array $context = [])
    {
        $data->add($object);
    }

    public static function getSupportedTypes(): array
    {
        return [Data::class];
    }
}
