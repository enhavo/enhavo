<?php

namespace Enhavo\Bundle\ResourceBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_CLASS|Attribute::IS_REPEATABLE)]
class Duplicate
{
    public function __construct(
        public string $type,
        public array $options = [],
    ) {}
}
