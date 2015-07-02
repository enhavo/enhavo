<?php

namespace spec\enhavo\AdminBundle\Viewer;


use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;

class ViewerFactorySpec extends ObjectBehavior
{
    function it_is_initializable(Container $container, RequestStack $requestStack)
    {
        $this->beConstructedWith($container, $requestStack, array());
        $this->shouldHaveType('enhavo\AdminBundle\Viewer\ViewerFactory');
    }

    function it_should_create_a_viewer(Container $container, RequestStack $requestStack, Request $request)
    {
        $viewerList = array(
            'table' => 'enhavo\AdminBundle\Viewer\TableViewer',
            'create' => 'enhavo\AdminBundle\Viewer\CreateViewer',
            'app' => 'enhavo\AdminBundle\Viewer\AppViewer',
            'edit' => 'enhavo\AdminBundle\Viewer\EditViewer'
        );

        $this->beConstructedWith($container, $requestStack, $viewerList);
        $request->get('_route')->willReturn('current_route');
        $requestStack->getMasterRequest()->willReturn($request);
        $this->create('table')->shouldReturnAnInstanceOf('enhavo\AdminBundle\Viewer\TableViewer');
        $this->shouldThrow('enhavo\AdminBundle\Exception\ViewerNotFoundException')->during('create', array('this_viewer_is_not_known'));
    }
}
