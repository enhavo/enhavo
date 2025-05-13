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

use Enhavo\Bundle\AppBundle\Action\Type\UpdateActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateActionTypeTest extends TestCase
{
    use UrlActionTypeFactoryTrait;

    private function createDependencies(): UpdateActionTypeDependencies
    {
        $dependencies = new UpdateActionTypeDependencies();
        $dependencies->routeResolver = $this->getMockBuilder(RouteResolverInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance(UpdateActionTypeDependencies $dependencies): UpdateActionType
    {
        return new UpdateActionType(
            $dependencies->routeResolver,
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            $this->createUrlActionType($this->createUrlActionTypeDependencies()),
        ], [
            'route' => 'edit_route',
        ]);

        $viewData = $action->createViewData(new ResourceMock(1));

        $this->assertEquals('/edit_route?id=1', $viewData['url']);
    }

    public function testName()
    {
        $this->assertEquals('update', UpdateActionType::getName());
    }
}

class UpdateActionTypeDependencies
{
    public RouteResolverInterface|MockObject $routeResolver;
}
