<?php

namespace spec\Enhavo\Bundle\AdminBundle\Viewer;


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
        $this->shouldHaveType('Enhavo\Bundle\AdminBundle\Viewer\ViewerFactory');
    }

    function it_should_create_a_viewer(Container $container, RequestStack $requestStack, Request $request)
    {
        $viewerList = array(
            'table' => 'Enhavo\Bundle\AdminBundle\Viewer\TableViewer',
            'create' => 'Enhavo\Bundle\AdminBundle\Viewer\CreateViewer',
            'app' => 'Enhavo\Bundle\AdminBundle\Viewer\AppViewer',
            'edit' => 'Enhavo\Bundle\AdminBundle\Viewer\EditViewer'
        );

        $this->beConstructedWith($container, $requestStack, $viewerList);
        $request->get('_route')->willReturn('current_route');
        $requestStack->getMasterRequest()->willReturn($request);
        $this->create('table')->shouldReturnAnInstanceOf('Enhavo\Bundle\AdminBundle\Viewer\TableViewer');
        $this->shouldThrow('Enhavo\Bundle\AdminBundle\Exception\ViewerNotFoundException')->during('create', array('this_viewer_is_not_known'));
    }
}
