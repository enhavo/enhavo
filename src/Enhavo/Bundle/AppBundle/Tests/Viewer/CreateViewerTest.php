<?php

namespace spec\Enhavo\Bundle\AppBundle\Tests\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\CreateViewer;

class CreateViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new CreateViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\CreateViewer', $viewer);
    }

    /**
     * Test if the CreateViewer pass back the correct value for function getParameters
     *
     */
    function testReturnParameters()
    {
        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();

        $configParser->method('get')->will($this->returnValueMap(
            [
                ['tabs', 'tabs'],
                ['form.template', 'form_template'],
                ['form.action', 'form_action_route'],
                ['buttons', array()],
                ['parameters', array()]
            ]
        ));

        $router = $this->getMockBuilder('Symfony\Component\Routing\Router')
            ->disableOriginalConstructor()
            ->getMock();
        $router->method('generate')->with('form_action_route')->willReturn('some_action_url');

        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $container->method('get')->with('router')->willReturn($router);

        $viewer = new CreateViewer();
        $viewer->setConfig($configParser);
        $viewer->setForm('form');
        $viewer->setContainer($container);

        $parameters = $viewer->getParameters();

        $this->assertArraySubset([
            'tabs' => 'tabs',
            'buttons' => array(),
            'form_action' => 'some_action_url',
            'form_template' => 'form_template',
            'form' => 'form',
            'viewer' => $viewer,
            'sorting' => [
                'sortable' => false,
                'position' => 'position',
                'initial' => 'max'
            ]
        ], $parameters);
    }
}
