<?php

namespace Enhavo\Bundle\AppBundle\Type;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;

class TypeCollectorTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $collector = new TypeCollector('typeName');
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Type\TypeCollector', $collector);
    }

    /**
     * Test basic add and getCollection function
     */
    function testAddAndGetCollection()
    {
        $widgetMock = $this->buildTypeMock('type1');

        $collector = new TypeCollector();
        $collector->add($this->buildTypeMock('type1'));
        $collector->add($this->buildTypeMock('type2'));
        $widgets = $collector->getCollection();

        $this->assertCount(2, $widgets);
        $this->assertEquals('type1', $widgets[0]->getType());
        $this->assertEquals('type2', $widgets[1]->getType());
    }

    /**
     * Testing receiving correct type by 'getType' method
     */
    function testGettingType()
    {
        $collector = new TypeCollector();
        $collector->add($this->buildTypeMock('type1'));
        $collector->add($this->buildTypeMock('type2'));
        $collector->add($this->buildTypeMock('type3'));

        $type = $collector->getType('type2');
        $this->assertNotNull($type);
        $this->assertEquals('type2', $type->getType());

        $type = $collector->getType('type1');
        $this->assertNotNull($type);
        $this->assertEquals('type1', $type->getType());
    }

    /**
     * @expectedException Enhavo\Bundle\AppBundle\Exception\TypeNotFoundException
     */
    function testGettingNonExistingType()
    {
        $collector = new TypeCollector();
        $collector->add($this->buildTypeMock('type1'));

        $collector->getType('NonExistingType');
    }

    protected function buildTypeMock($typeName)
    {
        $typeMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Type\TypeInterface')->getMock();
        $typeMock->method('getType')->willReturn($typeName);

        return $typeMock;
    }
}