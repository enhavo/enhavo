<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

class TargetValue
{
    private mixed $value = null;

    public function __construct(
        private mixed $originalValue = null,
    )
    {
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getOriginalValue(): mixed
    {
        return $this->originalValue;
    }
}
