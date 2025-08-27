<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData;

use Enhavo\Bundle\ContentBundle\StructuredData\Type\BaseStructuredDataType;
use Enhavo\Bundle\ContentBundle\StructuredData\Type\ThingStructuredDataType;
use Enhavo\Component\Type\AbstractType;

/**
 * @property StructuredDataTypeInterface $parent
 */
abstract class AbstractStructuredDataType extends AbstractType implements StructuredDataTypeInterface
{
    public function buildData(array $options, object $model, StructuredDataBag $bag, Context $context): void
    {

    }

    public function getTypeName(array $options): ?string
    {
        return $this->parent->getTypeName($options);
    }

    public static function getParentType(): ?string
    {
        return ThingStructuredDataType::class;
    }
}
