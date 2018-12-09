<?php

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\Widget\MultiplePropertyWidget;
use Enhavo\Bundle\AppBundle\Mock\EntityMock;
use PHPUnit\Framework\TestCase;

class MultipleWidgetTest extends TestCase
{
    function testInitialize()
    {
        $widget = new MultiplePropertyWidget();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Table\Widget\MultiplePropertyWidget', $widget);
    }

    function testRender()
    {
        $object = new EntityMock();
        $object->setName('myName');
        $object->setId(1);

        $options = [
            'properties' => [
                'name',
                'id'
            ],
        ];

        $widget = new MultiplePropertyWidget();
        $this->assertEquals('myName,1', $widget->render($options, $object));
    }

    function testType()
    {
        $widget = new MultiplePropertyWidget();
        $this->assertEquals('multiple_property', $widget->getType());
    }
}