<?php

namespace Enhavo\Bundle\SearchBundle\Filter\Type;

use Enhavo\Bundle\SearchBundle\Filter\FilterDataBuilder;
use Enhavo\Bundle\SearchBundle\Filter\FilterTypeInterface;
use Enhavo\Component\Type\AbstractType;

abstract class AbstractFilterType extends AbstractType implements FilterTypeInterface
{
    public function buildFilter(array $options, $model, string $key, FilterDataBuilder $builder): void
    {

    }

    public function buildField(array $options, string $key, FilterDataBuilder $builder): void
    {

    }
}
