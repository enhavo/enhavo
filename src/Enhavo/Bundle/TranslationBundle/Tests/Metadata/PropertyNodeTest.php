<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Metadata;

use Enhavo\Bundle\TranslationBundle\Metadata\PropertyNode;
use PHPUnit\Framework\TestCase;

class PropertyNodeTest extends TestCase
{
    public function testGetDefaultOptions()
    {
        $property = new PropertyNode();
        $this->assertEquals([], $property->getOptions());
    }
}
