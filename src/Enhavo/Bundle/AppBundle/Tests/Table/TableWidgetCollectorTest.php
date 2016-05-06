<?php

namespace Enhavo\Bundle\AppBundle\Tests\Table;

use Enhavo\Bundle\AppBundle\Table\TableWidgetCollector;

class TableWidgetCollectorTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $collector = new TableWidgetCollector();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Table\TableWidgetCollector', $collector);
    }

    /**
     * Test basic add and getCollection function
     */
    function testAddAndGetCollectionType()
    {
        $widgetMock = $this->buildWidgetMock('type1');

        $collector = new TableWidgetCollector();
        $collector->add($widgetMock);
        $widgets = $collector->getCollection();

        $this->assertCount(1, $widgets);
        $this->assertEquals('type1', $widgets[0]->getType());
    }

    function testGetWidget()
    {
        $collector = new TableWidgetCollector();
        $collector->add($this->buildWidgetMock('type1'));
        $collector->add($this->buildWidgetMock('type2'));
        $collector->add($this->buildWidgetMock('type3'));

        $widget = $collector->getWidget('type2');
        $this->assertNotNull($widget);
        $this->assertEquals('type2', $widget->getType());
    }

    /**
     * @expectedException \Enhavo\Bundle\AppBundle\Exception\TableWidgetException
     */
    function testNonExistingGetWidget()
    {
        $collector = new TableWidgetCollector();
        $collector->add($this->buildWidgetMock('type1'));

        $collector->getWidget('NonExistingType');
    }

    protected function buildWidgetMock($typeName)
    {
        $widgetMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Table\TableWidgetInterface')->getMock();
        $widgetMock->method('getType')->willReturn($typeName);

        return $widgetMock;
    }
}