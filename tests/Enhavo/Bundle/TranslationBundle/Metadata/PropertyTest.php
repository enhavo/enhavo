<?php
/**
 * PropertyTest.php
 *
 * @since 19/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Metadata;

use Symfony\Component\PropertyAccess\PropertyPath;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    public function testUnderscoreName()
    {
        $property = new Property();
        $property->setName('camelCase');
        $this->assertEquals('camel_case', $property->getUnderscoreName());

        $property = new Property(new PropertyPath('CamelCase'));
        $this->assertEquals('camel_case', $property->getUnderscoreName());
    }

    public function testStrategy()
    {
        $property = new Property();
        $property->setStrategy('TestStrategy');
        $this->assertEquals('TestStrategy', $property->getStrategy());
    }

    public function testOption()
    {
        $property = new Property();
        $property->setOptions([
            'option' => 'one',
            'other' => 'two'
        ]);
        $this->assertArraySubset([
            'option' => 'one',
            'other' => 'two'
        ], $property->getOptions());

        $property->setOption('option', 'huhu');
        $this->assertArraySubset([
            'option' => 'huhu',
            'other' => 'two'
        ], $property->getOptions());
    }
}