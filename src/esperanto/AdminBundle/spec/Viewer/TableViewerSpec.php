<?php

namespace spec\esperanto\AdminBundle\Viewer;

use esperanto\AdminBundle\Viewer\ConfigParser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use esperanto\AdminBundle\Entity\Route;

class TableViewerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('esperanto\AdminBundle\Viewer\TableViewer');
    }

    function it_should_return_value_by_property_on_object()
    {
        $object = new Route();
        $object->setName('my name is route');
        $this->getProperty($object, 'name')->shouldReturn('my name is route');

        $this->shouldThrow('esperanto\AdminBundle\Exception\PropertyNotExistsException')->during(
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
                'property' => 'id'
            )
        );

        $templateVars = array(
            'hello' => 'world',
            'foo' => 'bar'
        );

        $configParser->get('table.columns')->willReturn($columns);
        $configParser->get('parameters')->willReturn($templateVars);

        $this->setConfig($configParser);
        $this->getParameters()->shouldHaveKeyWithValue('columns', $columns);
        $this->getParameters()->shouldHaveKeyWithValue('hello', 'world');
        $this->getParameters()->shouldHaveKeyWithValue('foo', 'bar');
    }
}