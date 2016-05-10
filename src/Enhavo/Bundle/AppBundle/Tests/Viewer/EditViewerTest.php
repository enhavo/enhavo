<?php

namespace spec\Enhavo\Bundle\AppBundle\Tests\Viewer;

use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\AppBundle\Viewer\EditViewer;

class EditViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new EditViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\EditViewer', $viewer);
    }

    /**
     * Test if theEditViewer pass back the correct value for function getParameters
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
                ['form.delete', 'form_delete_route'],
                ['buttons', array()],
                ['parameters', array()],
                ['translationDomain', 'EnhavoAppBundle']
            ]
        ));

        $router = $this->getMockBuilder('Symfony\Component\Routing\Router')
            ->disableOriginalConstructor()
            ->getMock();

        $router->method('generate')->willReturnCallback(function($name) {
            if($name == 'form_action_route') {
                return 'some_action_url';
            }
            if($name == 'form_delete_route') {
                return 'some_delete_url';
            }
            return null;
        });

        $securityContext = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock();
        $securityContext->method('isGranted')->willReturn(true);

        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $container->method('get')->willReturnCallback(function($id) use ($router, $securityContext) {
            if('router' == $id) {
                return $router;
            }
            if('security.context' == $id) {
                return $securityContext;
            }
            return null;
        });

        $resource = new EntityMock();
        $resource->setId(1);

        $viewer = new EditViewer();
        $viewer->setContainer($container);
        $viewer->setConfig($configParser);
        $viewer->setForm('form');
        $viewer->setResource($resource);

        $parameters = $viewer->getParameters();

        $this->assertArraySubset([
            'tabs' => 'tabs',
            'buttons' => array(),
            'form_action' => 'some_action_url',
            'form_template' => 'form_template',
            'form_delete' => 'some_delete_url',
            'form' => 'form',
            'viewer' => $viewer,
            'data' => $resource,
            'translationDomain' => 'EnhavoAppBundle'
        ], $parameters);
    }
}
