<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Filter;

class FilterField
{
    public const FIELD_TYPE_STRING = 'string';
    public const FIELD_TYPE_DATE = 'date';

    public function __construct(
        private string $key,
        private string $fieldType,
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getFieldType(): string
    {
        return $this->fieldType;
    }
}
