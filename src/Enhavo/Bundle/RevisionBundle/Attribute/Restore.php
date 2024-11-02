<?php

namespace Enhavo\Bundle\RevisionBundle\Attribute;


use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Restore
{
    public function __construct(
        public string $type,
        public array $options = [],
    ) {}
}
