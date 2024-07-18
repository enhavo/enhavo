<?php

namespace Enhavo\Bundle\ApiBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;

class DataDataNormalizer extends AbstractDataNormalizer
{
    public function buildData(Data $data, $object, string $format = null, array $context = [])
    {
        $data->add($object);
    }

    public static function getSupportedTypes(): array
    {
        return [Data::class];
    }
}
