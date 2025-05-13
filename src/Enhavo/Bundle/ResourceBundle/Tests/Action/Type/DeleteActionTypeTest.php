<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Action\Type\DeleteActionType;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class DeleteActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new DeleteActionTypeDependencies();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        $dependencies->tokenManager = $this->getMockBuilder(CsrfTokenManager::class)->getMock();
        $dependencies->routeResolver = $this->getMockBuilder(RouteResolverInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance(DeleteActionTypeDependencies $dependencies)
    {
        return new DeleteActionType(
            $dependencies->tokenManager,
            $dependencies->router,
            $dependencies->routeResolver,
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->tokenManager->method('getToken')->willReturn(new TestCsrfToken());
        $dependencies->routeResolver->method('getRoute')->willReturn('delete_route');
        $dependencies->router->method('generate')->willReturnCallback(function ($name) {
            if ('delete_route' === $name) {
                return '/delete_route?id=1';
            }

            return null;
        });
        $instance = $this->createInstance($dependencies);

        $action = new Action($instance, [], [
            'icon' => 'test_icon',
            'model' => 'TestModel',
            'label' => 'Test',
        ]);

        $viewData = $action->createViewData(new ResourceMock(1));

        $this->assertEquals('csrfTok3n', $viewData['token']);
        $this->assertEquals('/delete_route?id=1', $viewData['url']);
    }

    public function testName()
    {
        $this->assertEquals('delete', DeleteActionType::getName());
    }
}

class DeleteActionTypeDependencies
{
    public RouterInterface|MockObject $router;
    public CsrfTokenManager|MockObject $tokenManager;
    public RouteResolverInterface|MockObject $routeResolver;
}

class TestCsrfToken extends CsrfToken
{
    public function __construct()
    {
    }

    public function getValue(): string
    {
        return 'csrfTok3n';
    }
}
