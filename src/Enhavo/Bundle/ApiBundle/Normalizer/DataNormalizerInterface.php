<?php

namespace Enhavo\Bundle\ApiBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;

interface DataNormalizerInterface
{
    public function buildData(Data $data, $object, string $format = null, array $context = []);

    public static function getSupportedTypes(): array;
}
