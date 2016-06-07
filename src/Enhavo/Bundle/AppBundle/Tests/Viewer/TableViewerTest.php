<?php

namespace spec\Enhavo\Bundle\AppBundle\Tests\Viewer;

use Enhavo\Bundle\AppBundle\Table\TableWidgetCollector;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\AppBundle\Viewer\TableViewer;

class TableViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new TableViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\TableViewer', $viewer);
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
     * Test if the column widgets have the default type, if no one is set
     */
    function testReturnDefaultType()
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
                'type' => 'property'
            ),
            'name' => array(
                'property' => 'name',
                'type' => 'property'
            )
        );

        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();
        $configParser->method('get')->will($this->returnValueMap([
            ['table.columns', $definedColumns],
            ['parameters', array()],
        ]));

        $viewer = new TableViewer();
        $viewer->setConfig($configParser);

        $parameters = $viewer->getParameters();
        $this->assertArrayHasKey('columns', $parameters);
        $this->assertArraySubset($resultColumns, $parameters['columns']);
    }

    /**
     * Get a container with collector that contains passed widgets
     *
     * @param $widgets
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getContainerWithCollectorMock($widgets)
    {
        $collector = new TypeCollector();
        foreach($widgets as $widget) {
            $collector->add($widget);
        }

        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $container->method('get')->willReturn($collector);

        return $container;
    }

    public function testDefaultColumns()
    {
        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();

        $viewer = new TableViewer();
        $viewer->setConfig($configParser);

        $defaultColumn = array(
            'id' => array(
                'label' => 'id',
                'property' => 'id',
                'type' => 'property'
            )
        );

        $parameters = $viewer->getParameters();
        $this->assertArrayHasKey('columns', $parameters);
        $this->assertArraySubset($defaultColumn, $parameters['columns']);
    }

    public function testDefaultWithSorting()
    {
        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();
        $configParser->method('get')->will($this->returnValueMap([
            ['table.sorting', ['sortable' => true]],
        ]));

        $viewer = new TableViewer();
        $viewer->setConfig($configParser);

        $defaultColumn = array(
            'id' => array(
                'label' => 'id',
                'property' => 'id',
                'type' => 'property'
            ),
            'position' => array(
                'type' => 'position'
            )
        );

        $parameters = $viewer->getParameters();
        $this->assertArrayHasKey('columns', $parameters);
        $this->assertArraySubset($defaultColumn, $parameters['columns']);
    }

    public function testGetDefaultTableWidth()
    {
        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();

        $viewer = new TableViewer();
        $viewer->setConfig($configParser);

        $this->assertEquals(12, $viewer->getTableWidth());

    }
}