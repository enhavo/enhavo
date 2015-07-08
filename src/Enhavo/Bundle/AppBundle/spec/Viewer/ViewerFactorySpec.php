<?php

namespace spec\Enhavo\Bundle\AppBundle\Viewer;


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
        $this->shouldHaveType('Enhavo\Bundle\AppBundle\Viewer\ViewerFactory');
    }

    function it_should_create_a_viewer(Container $container, RequestStack $requestStack, Request $request)
    {
        $viewerList = array(
            'table' => 'Enhavo\Bundle\AppBundle\Viewer\TableViewer',
            'create' => 'Enhavo\Bundle\AppBundle\Viewer\CreateViewer',
            'app' => 'Enhavo\Bundle\AppBundle\Viewer\AppViewer',
            'edit' => 'Enhavo\Bundle\AppBundle\Viewer\EditViewer'
        );

        $this->beConstructedWith($container, $requestStack, $viewerList);
        $request->get('_route')->willReturn('current_route');
        $requestStack->getMasterRequest()->willReturn($request);
        $this->create('table')->shouldReturnAnInstanceOf('Enhavo\Bundle\AppBundle\Viewer\TableViewer');
    }

    function it_should_throw_an_exception_if_no_class_was_found(Container $container, RequestStack $requestStack, Request $request)
    {
        $viewerList = array(
            'table' => 'Enhavo\Bundle\AppBundle\Viewer\TableViewer',
            'create' => 'Enhavo\Bundle\AppBundle\Viewer\CreateViewer',
            'app' => 'Enhavo\Bundle\AppBundle\Viewer\AppViewer',
            'edit' => 'Enhavo\Bundle\AppBundle\Viewer\EditViewer'
        );

        $this->beConstructedWith($container, $requestStack, $viewerList);
        $request->get('_route')->willReturn('current_route');
        $requestStack->getMasterRequest()->willReturn($request);
        $this->shouldThrow('Enhavo\Bundle\AppBundle\Exception\ViewerNotFoundException')->during('create', array('this_viewer_is_not_known'));
    }
}
