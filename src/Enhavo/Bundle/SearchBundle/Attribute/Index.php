<?php

namespace Enhavo\Bundle\SearchBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_CLASS|Attribute::IS_REPEATABLE)]
class Index
{
    public function __construct(
        public string $type,
        public array $options = [],
    ) {

    }
}
