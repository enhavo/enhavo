<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\RouteResolver;

use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteNameRouteResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class RouteNameRouteResolverTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new RouteNameRouteResolverDependencies();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function createInstance(RouteNameRouteResolverDependencies $dependencies)
    {
        $instance = new RouteNameRouteResolver(
            $dependencies->requestStack,
            $dependencies->router,
        );

        return $instance;
    }

    public function testReturnsNullWhenNoRequest()
    {
        $dependencies = $this->createDependencies();
        $dependencies->requestStack->method('getMainRequest')->willReturn(null);

        $instance = $this->createInstance($dependencies);

        $this->assertNull($instance->getRoute('update'));
    }

    public function testReturnsNullWhenNoRouteName()
    {
        $dependencies = $this->createDependencies();

        $request = new Request();
        $dependencies->requestStack->method('getMainRequest')->willReturn($request);

        $instance = $this->createInstance($dependencies);

        $this->assertNull($instance->getRoute('update'));
    }

    public function testResolvesRouteByReplacingLastSegment()
    {
        $dependencies = $this->createDependencies();

        $request = new Request();
        $request->attributes->set('_route', 'admin_api_article_index');
        $dependencies->requestStack->method('getMainRequest')->willReturn($request);

        $routeCollection = new RouteCollection();
        $routeCollection->add('admin_api_article_update', new Route('/admin/api/article/update'));
        $dependencies->router->method('getRouteCollection')->willReturn($routeCollection);

        $instance = $this->createInstance($dependencies);

        $this->assertEquals('admin_api_article_update', $instance->getRoute('update'));
    }

    public function testResolvesRouteByReplacingLastTwoSegment()
    {
        $dependencies = $this->createDependencies();

        $request = new Request();
        $request->attributes->set('_route', 'admin_api_article_translate_resource');
        $dependencies->requestStack->method('getMainRequest')->willReturn($request);

        $routeCollection = new RouteCollection();
        $routeCollection->add('admin_api_article_update', new Route('/admin/api/article/update'));
        $dependencies->router->method('getRouteCollection')->willReturn($routeCollection);

        $instance = $this->createInstance($dependencies);

        $this->assertEquals('admin_api_article_update', $instance->getRoute('update'));
    }

    public function testReturnsNullWhenResolvedRouteDoesNotExist()
    {
        $dependencies = $this->createDependencies();

        $request = new Request();
        $request->attributes->set('_route', 'admin_api_article_index');
        $dependencies->requestStack->method('getMainRequest')->willReturn($request);

        $routeCollection = new RouteCollection();
        $dependencies->router->method('getRouteCollection')->willReturn($routeCollection);

        $instance = $this->createInstance($dependencies);

        $this->assertNull($instance->getRoute('update'));
    }

    public function testRemovesApiSegmentWhenApiContextIsFalse()
    {
        $dependencies = $this->createDependencies();

        $request = new Request();
        $request->attributes->set('_route', 'admin_api_article_index');
        $dependencies->requestStack->method('getMainRequest')->willReturn($request);

        $routeCollection = new RouteCollection();
        $routeCollection->add('admin_article_update', new Route('/admin/article/update'));
        $dependencies->router->method('getRouteCollection')->willReturn($routeCollection);

        $instance = $this->createInstance($dependencies);

        $this->assertEquals('admin_article_update', $instance->getRoute('update', ['api' => false]));
    }

    public function testKeepsApiSegmentWhenApiContextIsTrue()
    {
        $dependencies = $this->createDependencies();

        $request = new Request();
        $request->attributes->set('_route', 'admin_api_article_index');
        $dependencies->requestStack->method('getMainRequest')->willReturn($request);

        $routeCollection = new RouteCollection();
        $routeCollection->add('admin_api_article_update', new Route('/admin/api/article/update'));
        $dependencies->router->method('getRouteCollection')->willReturn($routeCollection);

        $instance = $this->createInstance($dependencies);

        $this->assertEquals('admin_api_article_update', $instance->getRoute('update', ['api' => true]));
    }
}

class RouteNameRouteResolverDependencies
{
    public RequestStack|MockObject $requestStack;
    public RouterInterface|MockObject $router;
}
