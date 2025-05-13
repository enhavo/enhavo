<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\PreviewActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\RouterMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;

class PreviewActionTypeTest extends TestCase
{
    use UrlActionTypeFactoryTrait;

    private function createDependencies(): PreviewActionTypeDependencies
    {
        $dependencies = new PreviewActionTypeDependencies();
        $dependencies->routeResolver = $this->getMockBuilder(RouteResolverInterface::class)->getMock();
        $dependencies->resourceExpressionLanguage = new ResourceExpressionLanguage();
        $dependencies->router = new RouterMock();

        return $dependencies;
    }

    private function createInstance(PreviewActionTypeDependencies $dependencies): PreviewActionType
    {
        return new PreviewActionType(
            $dependencies->router,
            $dependencies->routeResolver,
            $dependencies->resourceExpressionLanguage,
        );
    }

    public function testCreateViewWithNullId()
    {
        $dependencies = $this->createDependencies();
        $dependencies->routeResolver->method('getRoute')->willReturn('api_route');
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            $this->createUrlActionType($this->createUrlActionTypeDependencies()),
        ], []);

        $viewData = $action->createViewData(new ResourceMock(null));

        $this->assertEquals('/enhavo_app_admin_resource_preview', $viewData['url']);
        $this->assertEquals('/api_route', $viewData['apiUrl']);
    }

    public function testCreateViewWithId()
    {
        $dependencies = $this->createDependencies();
        $dependencies->routeResolver->method('getRoute')->willReturn('api_route');
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            $this->createUrlActionType($this->createUrlActionTypeDependencies()),
        ], []);

        $viewData = $action->createViewData(new ResourceMock(12));

        $this->assertEquals('/enhavo_app_admin_resource_preview', $viewData['url']);
        $this->assertEquals('/api_route?id=12', $viewData['apiUrl']);
    }

    public function testNoApiRouteFound()
    {
        $dependencies = $this->createDependencies();
        $dependencies->routeResolver->method('getRoute')->willReturn(null);
        $type = $this->createInstance($dependencies);

        $this->expectException(\Exception::class);

        $action = new Action($type, [
            $this->createUrlActionType($this->createUrlActionTypeDependencies()),
        ], []);

        $action->createViewData();
    }

    public function testName()
    {
        $this->assertEquals('preview', PreviewActionType::getName());
    }
}

class PreviewActionTypeDependencies
{
    public RouterInterface|MockObject $router;
    public RouteResolverInterface|MockObject $routeResolver;
    public ResourceExpressionLanguage|MockObject $resourceExpressionLanguage;
}
