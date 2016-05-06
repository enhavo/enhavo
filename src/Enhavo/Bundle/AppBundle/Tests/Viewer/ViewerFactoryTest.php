<?php

namespace spec\Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\ViewerFactory;

class ViewerFactoryTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $requestStack = $this->getRequestStackMock();

        $factory = new ViewerFactory($container, $requestStack, []);
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\ViewerFactory', $factory);
    }

    /**
     * Test if create function will create and return the right viewer
     */
    function testCreateViewer()
    {
        $viewerList = $this->getViewerList();
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $requestStack = $this->getRequestStackMock();

        $factory = new ViewerFactory($container, $requestStack, $viewerList);
        $viewer = $factory->create('table');
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\TableViewer', $viewer);
        $this->assertEquals($container, $viewer->getContainer());
    }

    /**
     * Test if you try to create from a viewer type, that does not exists
     *
     * @expectedException \Enhavo\Bundle\AppBundle\Exception\ViewerNotFoundException
     */
    function testCreateViewerWithTypeThatDoesNotExist()
    {
        $viewerList = $this->getViewerList();
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $requestStack = $this->getRequestStackMock();

        $factory = new ViewerFactory($container, $requestStack, $viewerList);
        $factory->create('notExistingType');
    }

    protected function getRequestStackMock()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->method('get')->willReturn('current_route');

        $requestStack = $this->getMockBuilder('Symfony\Component\HttpFoundation\RequestStack')->getMock();
        $requestStack->method('getMasterRequest')->willReturn($request);

        return $requestStack;
    }

    protected function getViewerList()
    {
        return array(
            'table' => 'Enhavo\Bundle\AppBundle\Viewer\TableViewer',
            'create' => 'Enhavo\Bundle\AppBundle\Viewer\CreateViewer',
            'app' => 'Enhavo\Bundle\AppBundle\Viewer\AppViewer',
            'edit' => 'Enhavo\Bundle\AppBundle\Viewer\EditViewer'
        );
    }
}
