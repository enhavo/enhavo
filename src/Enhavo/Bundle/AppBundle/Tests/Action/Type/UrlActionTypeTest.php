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

use Enhavo\Bundle\AppBundle\Action\Type\UrlActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\TestCase;

class UrlActionTypeTest extends TestCase
{
    use UrlActionTypeFactoryTrait;

    public function createDependencies(): UrlActionTypeDependencies
    {
        return $this->createUrlActionTypeDependencies();
    }

    public function createInstance(UrlActionTypeDependencies $dependencies): UrlActionType
    {
        return $this->createUrlActionType($dependencies);
    }

    public function testUrl()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [], [
            'route' => 'url_route',
        ]);

        $viewData = $action->createViewData();

        $this->assertEquals('/url_route', $viewData['url']);
    }

    public function testUrlWithParameters()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [], [
            'route' => 'url_route',
            'route_parameters' => [
                'key' => 'value',
            ],
        ]);

        $viewData = $action->createViewData();

        $this->assertEquals('/url_route?key=value', $viewData['url']);
    }

    public function testUrlWithResourceParameters()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [], [
            'route' => 'url_route',
            'route_parameters' => [
                'id' => 'expr:resource.getId()',
            ],
        ]);

        $resource = new ResourceMock(12);
        $viewData = $action->createViewData($resource);

        $this->assertEquals('/url_route?id=12', $viewData['url']);
    }
}
