<?php

namespace Enhavo\Bundle\AppBundle\Maker\Analyze;

class UseItem
{
    public function __construct(
        private string $name
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
