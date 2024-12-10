<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\DuplicateActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Tests\Action\Type\BaseActionTypeFactoryTrait;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\RouterMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DuplicateActionTypeTest extends TestCase
{
    use UrlActionTypeFactoryTrait;
    use BaseActionTypeFactoryTrait;

    private function createDependencies()
    {
        $dependencies = new DuplicateActionTypeDependencies();
        $dependencies->tokenManager = $this->getMockBuilder(CsrfTokenManagerInterface::class)->getMock();
        $dependencies->router = new RouterMock();
        $dependencies->routeResolver = $this->getMockBuilder(RouteResolverInterface::class)->getMock();
        $dependencies->expressionLanguage = new ResourceExpressionLanguage();
        return $dependencies;
    }

    private function createInstance(DuplicateActionTypeDependencies $dependencies)
    {
        return new DuplicateActionType(
            $dependencies->router,
            $dependencies->routeResolver,
            $dependencies->expressionLanguage,
            $dependencies->tokenManager,
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->routeResolver->method('getRoute')->willReturn('duplicate_route');

        $dependencies->tokenManager->method('getToken')->willReturn(new CsrfToken('id123', 'value123'));
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            $this->createBaseActionType($this->createBaseActionTypeDependencies()),
            $this->createUrlActionType($this->createUrlActionTypeDependencies()),

        ], [
            'route' => 'duplicate_route'
        ]);

        $viewData = $action->createViewData(new ResourceMock(1));

        $this->assertEquals('label.duplicate.translated', $viewData['label']);
        $this->assertEquals('/duplicate_route?id=1', $viewData['url']);
        $this->assertEquals('value123', $viewData['token']);
    }

    public function testName()
    {
        $this->assertEquals('duplicate', DuplicateActionType::getName());
    }
}

class DuplicateActionTypeDependencies
{
    public CsrfTokenManagerInterface|MockObject $tokenManager;
    public RouterInterface|MockObject $router;
    public RouteResolverInterface|MockObject $routeResolver;
    public ResourceExpressionLanguage|MockObject $expressionLanguage;
}
