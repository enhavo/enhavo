<?php

namespace Enhavo\Component\Metadata\Tests\Fixtures;

class AttributeTestClass
{
    #[AttributeClass('myType', ['option1' => 'valueOption1'])]
    private string $property = 'Test';
}
