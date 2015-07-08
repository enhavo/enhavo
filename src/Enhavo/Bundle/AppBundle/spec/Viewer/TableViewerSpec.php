<?php

namespace spec\Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Config\ConfigParser;
use Enhavo\Bundle\AppBundle\spec\EntityMock;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Templating\EngineInterface;

class TableViewerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Enhavo\Bundle\AppBundle\Viewer\TableViewer');
    }

    function it_should_return_value_by_property_on_object()
    {
        $object = new EntityMock();
        $object->setName('my name is route');
        $this->getProperty($object, 'name')->shouldReturn('my name is route');

        $this->shouldThrow('Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException')->during(
            'getProperty',
            array(
                $object,
                'somePropertyWhichDoesNotExist'
            )
        );
    }

    function it_should_return_parameters(ConfigParser $configParser)
    {
        $columns = array(
            'id' => array(
                'property' => 'id',
                'width' => 1
            )
        );

        $templateVars = array(
            'hello' => 'world',
            'foo' => 'bar'
        );

        $configParser->get('table.columns')->willReturn($columns);
        $configParser->get('parameters')->willReturn($templateVars);
        $configParser->get('table.width')->willReturn(null);
        $configParser->setDefault($this->getDefaultConfig())->willReturn(null);

        $this->setConfig($configParser);
        $this->getParameters()->shouldHaveKeyWithValue('columns', $columns);
        $this->getParameters()->shouldHaveKeyWithValue('hello', 'world');
        $this->getParameters()->shouldHaveKeyWithValue('foo', 'bar');
    }

    function it_should_return_column_width(ConfigParser $configParser)
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

        $configParser->get('table.width')->willReturn(null);
        $configParser->get('table.columns')->willReturn($definedColumns);
        $configParser->get('parameters')->willReturn(array());
        $configParser->setDefault($this->getDefaultConfig())->willReturn(null);

        $this->setConfig($configParser);

        $this->getParameters()->shouldHaveKeyWithValue('columns', $resultColumns);
    }

    function it_should_render_widget(ConfigParser $configParser, Container $container, EngineInterface $engine)
    {
        $columns = array(
            'id' => array(
                'property' => 'id',
                'widget' => 'EnhavoAppBundle:Widget:id.html.twig'
            ),
        );
        $object = new EntityMock();
        $object->setName('something');
        $parameters = array(
            'data' => $object,
            'value' => 'something'
        );
        $template = 'EnhavoAppBundle:Widget:id.html.twig';

        $engine->render($template, $parameters)->willReturn('hello');
        $container->get('templating')->willReturn($engine);
        $configParser->get('table.width')->willReturn(null);
        $configParser->get('table.columns')->willReturn($columns);
        $configParser->get('parameters')->willReturn(array());
        $configParser->setDefault($this->getDefaultConfig())->willReturn(null);

        $this->setResource($object);
        $this->setConfig($configParser);
        $this->setContainer($container);

        $this->renderWidget('EnhavoAppBundle:Widget:id.html.twig', 'name', $object)->shouldBe('hello');
    }
}