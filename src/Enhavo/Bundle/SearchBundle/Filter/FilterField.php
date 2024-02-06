<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 25.08.18
 * Time: 21:53
 */

namespace Enhavo\Bundle\SearchBundle\Filter;


class FilterField
{
    const FIELD_TYPE_STRING = 'string';
    const FIELD_TYPE_DATE = 'date';

    public function __construct(
        private string $key,
        private string $fieldType,
    )
    {
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
