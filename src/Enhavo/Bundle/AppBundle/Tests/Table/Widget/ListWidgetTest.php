<?php

namespace Enhavo\Bundle\AppBundle\Tests\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\Widget\ListWidget;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;

class ListWidgetTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $widget = new ListWidget();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Table\Widget\ListWidget', $widget);
    }

    function testRender()
    {
        $entity = $this->getEntityMock();

        $options = [
            'property' => 'name'
        ];

        $widget = new ListWidget();
        $value = $widget->render($options, 'entities', $entity);
        $this->assertEquals('one,two', $value);
    }

    function testRenderWithDashSeparator()
    {
        $entity = $this->getEntityMock();

        $options = [
            'property' => 'name',
            'separator' => '-'
        ];

        $widget = new ListWidget();
        $value = $widget->render($options, 'entities', $entity);
        $this->assertEquals('one-two', $value);
    }

    protected function getEntityMock()
    {
        $childOne = new EntityMock();
        $childOne->setName('one');

        $childTwo = new EntityMock();
        $childTwo->setName('two');

        $entity = new EntityMock();
        $entity->addEntity($childOne);
        $entity->addEntity($childTwo);

        return $entity;
    }

    function testType()
    {
        $widget = new ListWidget();
        $this->assertEquals('list', $widget->getType());
    }
}