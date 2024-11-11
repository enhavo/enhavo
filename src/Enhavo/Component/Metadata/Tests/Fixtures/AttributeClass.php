<?php

namespace Enhavo\Component\Metadata\Tests\Fixtures;

#[Attribute(Attribute::TARGET_PROPERTY)]
class AttributeClass
{
    public function __construct(
        public string $type,
        public array $options = [],
    ) {}
}
