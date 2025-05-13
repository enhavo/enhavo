<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DashboardBundle\Tests\Widget\Type;

use Enhavo\Bundle\DashboardBundle\Dashboard\DashboardWidget;
use Enhavo\Bundle\DashboardBundle\Dashboard\Type\BaseDashboardWidgetType;
use PHPUnit\Framework\TestCase;

class BaseDashboardWidgetTypeTest extends TestCase
{
    public function testPermission()
    {
        $dependencies = $this->createDependencies();

        $widget = new DashboardWidget($this->createInstance($dependencies), [], [
            'permission' => 'ROLE_TEST',
            'component' => 'some-component',
        ]);

        $this->assertEquals('ROLE_TEST', $widget->getPermission());
    }

    public function testEnabled()
    {
        $dependencies = $this->createDependencies();

        $widget = new DashboardWidget($this->createInstance($dependencies), [], [
            'component' => 'some-component',
        ]);
        $this->assertTrue($widget->isEnabled());

        $widget = new DashboardWidget($this->createInstance($dependencies), [], [
            'enabled' => false,
            'component' => 'some-component',
        ]);
        $this->assertFalse($widget->isEnabled());
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();

        $widget = new DashboardWidget($this->createInstance($dependencies), [], [
            'component' => 'some-component',
            'model' => 'MyOwnModel',
        ]);

        $viewData = $widget->createViewData();

        $this->assertEquals('MyOwnModel', $viewData['model']);
        $this->assertEquals('some-component', $viewData['component']);
    }

    private function createInstance(BaseDashboardWidgetTypeDependencies $dependencies): BaseDashboardWidgetType
    {
        return new BaseDashboardWidgetType();
    }

    private function createDependencies(): BaseDashboardWidgetTypeDependencies
    {
        $dependencies = new BaseDashboardWidgetTypeDependencies();

        return $dependencies;
    }
}

class BaseDashboardWidgetTypeDependencies
{
}
