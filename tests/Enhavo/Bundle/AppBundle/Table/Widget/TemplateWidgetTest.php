<?php

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\Widget\TemplateWidget;
use Enhavo\Bundle\AppBundle\Mock\EntityMock;
use PHPUnit\Framework\TestCase;

class TemplateWidgetTest extends TestCase
{
    function testInitialize()
    {
        $widget = new TemplateWidget();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Table\Widget\TemplateWidget', $widget);
    }

    function testRender()
    {
        $twigEngine = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface')->getMock();
        $twigEngine->method('render')->willReturn('renderedText');

        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $container->method('get')->with('templating')->willReturn($twigEngine);

        $widget = new TemplateWidget();
        $widget->setContainer($container);

        $entity = new EntityMock();
        $options = [
            'template' => 'pathToTemplate',
            'property' => 'name'
        ];

        $value = $widget->render($options, $entity);
        $this->assertEquals('renderedText', $value);
    }

    function testType()
    {
        $widget = new TemplateWidget();
        $this->assertEquals('template', $widget->getType());
    }
}