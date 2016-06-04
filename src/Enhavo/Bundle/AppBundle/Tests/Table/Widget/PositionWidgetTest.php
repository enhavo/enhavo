<?php

namespace Enhavo\Bundle\AppBundle\Tests\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\Widget\PositionWidget;
use Enhavo\Bundle\AppBundle\Table\Widget\TemplateWidget;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;

class PositionWidgetTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $widget = new PositionWidget();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Table\Widget\PositionWidget', $widget);
    }

    function testRender()
    {
        $twigEngine = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface')->getMock();
        $twigEngine->method('render')->willReturn('renderedText');

        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $container->method('get')->with('templating')->willReturn($twigEngine);

        $widget = new PositionWidget();
        $widget->setContainer($container);

        $entity = new EntityMock();
        $options = [];

        $value = $widget->render($options, $entity);
        $this->assertEquals('renderedText', $value);
    }

    function testType()
    {
        $widget = new TemplateWidget();
        $this->assertEquals('template', $widget->getType());
    }
}