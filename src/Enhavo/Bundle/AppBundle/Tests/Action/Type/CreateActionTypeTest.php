<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\CreateActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;

class CreateActionTypeTest extends TestCase
{
    private function createDependencies(): CreateActionTypeDependencies
    {
        $dependencies = new CreateActionTypeDependencies;
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        $dependencies->routeResolver = $this->getMockBuilder(RouteResolverInterface::class)->getMock();
        $dependencies->expressionLanguage = new ResourceExpressionLanguage();
        return $dependencies;
    }

    private function createInstance(CreateActionTypeDependencies $dependencies): CreateActionType
    {
        return new CreateActionType(
            $dependencies->router,
            $dependencies->routeResolver,
            $dependencies->expressionLanguage,
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->router->method('generate')->willReturn('http://localhost/create');
        $dependencies->routeResolver->method('getRoute')->willReturn('create_route');
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [], []);

        $viewData = $action->createViewData();

        $this->assertEquals('http://localhost/create', $viewData['url']);
        $this->assertEquals('edit-view', $viewData['viewKey']);
        $this->assertEquals('_view', $viewData['target']);
    }

    public function testName()
    {
        $this->assertEquals('create', CreateActionType::getName());
    }
}

class CreateActionTypeDependencies
{
    public RouterInterface|MockObject $router;
    public RouteResolverInterface|MockObject $routeResolver;
    public ResourceExpressionLanguage|MockObject $expressionLanguage;
}
