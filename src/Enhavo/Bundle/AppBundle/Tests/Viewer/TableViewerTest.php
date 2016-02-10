<?php

namespace spec\Enhavo\Bundle\AppBundle\Tests\Viewer;

use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\AppBundle\Viewer\TableViewer;

class TableViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new TableViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\TableViewer', $viewer);
    }

    /**
     * Test if the function getProperty works well
     */
    function testPropertyAccessCorrectly()
    {
        $object = new EntityMock();
        $object->setName('my name is route');

        $viewer = new TableViewer();
        $this->assertEquals('my name is route', $viewer->getProperty($object, 'name'));
    }

    /**
     * Test if you try to access an not existing property, will throw an exception
     *
     * @expectedException \Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException
     */
    function testPropertyAccessIfNotExists()
    {
        $object = new EntityMock();
        $object->setName('my name is route');

        $viewer = new TableViewer();
        $viewer->getProperty($object, 'somePropertyWhichDoesNotExist');
    }

    /**
     * Test if the TableViewer pass back the correct value for function getParameters
     */
    function testReturnParameters()
    {
        $tableColumn = [
            'id' => [
                'property' => 'id',
                'width' => 1
            ]
        ];

        $parameters = [
            'hello' => 'world',
            'foo' => 'bar'
        ];

        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();
        $configParser->method('get')->will($this->returnValueMap([
            ['table.columns', $tableColumn],
            ['parameters', $parameters],
            ['table.width', null],
        ]));

        $viewer = new TableViewer();
        $viewer->setConfig($configParser);

        $parameters = $viewer->getParameters();

        $this->assertArrayHasKey('columns', $parameters);
        $this->assertArrayHasKey('hello', $parameters);
        $this->assertArrayHasKey('foo', $parameters);
    }

    /**
     * Test if the column width parameters will be added automatically,
     * if you don't add them via the config.
     */
    function testReturnColumnWidth()
    {
        $definedColumns = array(
            'id' => array(
                'property' => 'id'
            ),
            'name' => array(
                'property' => 'name',
                'width' => 5
            )
        );

        $resultColumns = array(
            'id' => array(
                'property' => 'id',
                'width' => 1
            ),
            'name' => array(
                'property' => 'name',
                'width' => 5
            )
        );

        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();
        $configParser->method('get')->will($this->returnValueMap([
            ['table.width', null],
            ['table.columns', $definedColumns],
            ['parameters', array()],
        ]));

        $viewer = new TableViewer();
        $viewer->setConfig($configParser);

        $parameters = $viewer->getParameters();
        $this->assertArrayHasKey('columns', $parameters);
        $this->assertArraySubset($resultColumns, $parameters['columns']);
    }

    function testRenderWidget()
    {
        $widget = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Table\TableWidgetInterface')->getMock();
        $widget->method('getType')->willReturn('template');
        $widget->method('render')->willReturn('hello');

        $container = $this->getContainerWithWidgetCollectorMock([$widget]);

        $options =[
            'type' => 'template',
            'template' => 'EnhavoAppBundle:Widget:id.html.twig',
        ];

        $item = new EntityMock();
        $item->setName('name');

        $viewer = new TableViewer();
        $viewer->setContainer($container);
        $this->assertEquals('hello', $viewer->renderWidget($options, 'name', $item ));
    }

    /**
     * Test if you try to access an widget type, that does not exists
     *
     * @expectedException \Enhavo\Bundle\AppBundle\Exception\TableWidgetException
     */
    function testNotFoundRenderWidget()
    {
        $widget = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Table\TableWidgetInterface')->getMock();
        $widget->method('getType')->willReturn('notExists');
        $widget->method('render')->willReturn('hello');

        $container = $this->getContainerWithWidgetCollectorMock([$widget]);

        $options =[
            'type' => 'template',
            'template' => 'EnhavoAppBundle:Widget:id.html.twig',
        ];

        $item = new EntityMock();
        $item->setName('name');

        $viewer = new TableViewer();
        $viewer->setContainer($container);

        $viewer->renderWidget($options, 'name', $item);
    }

    /**
     * Get a container with collector that contains passed widgets
     *
     * @param $widgets
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getContainerWithWidgetCollectorMock($widgets)
    {
        $collector = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Table\TableWidgetCollector')->getMock();
        $collector->method('getCollection')->willReturn($widgets);

        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $container->method('get')->willReturn($collector);

        return $container;
    }
}