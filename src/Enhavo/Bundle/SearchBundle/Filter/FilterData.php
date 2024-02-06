<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 25.08.18
 * Time: 21:53
 */

namespace Enhavo\Bundle\SearchBundle\Filter;


class FilterData
{
    public function __construct(
        private string $key,
        private string $value,
    ) {

    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
