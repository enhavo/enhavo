<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
