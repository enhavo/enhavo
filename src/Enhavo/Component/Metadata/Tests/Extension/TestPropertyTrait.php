<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-11
 * Time: 22:13
 */

namespace Enhavo\Component\Metadata\Tests\Extension;

use Enhavo\Component\Metadata\Extension\Property;
use Enhavo\Component\Metadata\Extension\PropertyInterface;
use Enhavo\Component\Metadata\Extension\PropertyTrait;
use PHPUnit\Framework\TestCase;

class TestPropertyTrait extends TestCase
{
    public function testGetterAndSetter()
    {
        $trait = new PropertyTraitClass();

        $trait->addProperty(new Property('name'));
        $trait->addProperty(new Property('type'));
        $this->assertCount(2, $trait->getProperties());

        $this->assertTrue($trait->hasProperty('name'));
        $this->assertFalse($trait->hasProperty('anything'));

        $this->assertEquals('name', $trait->getProperty('name')->getName());
    }
}

class PropertyTraitClass implements PropertyInterface
{
    use PropertyTrait;
}
