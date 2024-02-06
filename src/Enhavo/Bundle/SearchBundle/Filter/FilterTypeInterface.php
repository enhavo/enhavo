<?php

namespace Enhavo\Bundle\SearchBundle\Filter;

use Enhavo\Component\Type\TypeInterface;

interface FilterTypeInterface extends TypeInterface
{
    public function buildFilter(array $options, $model, string $key, FilterDataBuilder $builder): void;

    public function buildField(array $options, string $key, FilterDataBuilder $builder): void;
}
