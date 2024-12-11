<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

class SourceValue
{
    public function __construct(
        private mixed $value = null,
    )
    {
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
