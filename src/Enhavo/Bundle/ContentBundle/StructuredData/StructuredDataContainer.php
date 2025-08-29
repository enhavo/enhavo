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

use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property StructuredDataTypeInterface   $type
 * @property StructuredDataTypeInterface[] $parents
 */
class StructuredDataContainer extends AbstractContainerType
{
    public function buildData(object $model, StructuredDataBag $bag, Context $context): void
    {
        foreach ($this->parents as $parent) {
            $parent->buildData($this->options, $model, $bag, $context);
        }

        $this->type->buildData($this->options, $model, $bag, $context);
    }

    public function getTypeName(): ?string
    {
        return $this->type->getTypeName($this->options);
    }

    public function getGroups(): array
    {
        return $this->type->getGroups($this->options);
    }
}
