<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Widget;


use Enhavo\Bundle\DashboardBundle\Dashboard\AbstractDashboardWidgetType;
use Enhavo\Bundle\DashboardBundle\Dashboard\DashboardWidget;
use Enhavo\Bundle\DashboardBundle\Dashboard\DashboardWidgetTypeInterface;
use Enhavo\Component\Type\TypeInterface;
use PHPUnit\Framework\TestCase;

class WidgetTest extends TestCase
{
    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->type = $this->createTypeMock(false, 'ROLE_TEST');
        $dependencies->parents = [
            $this->createTypeMock(false, 'ROLE_TEST', 'parentKey', 'parentData')
        ];

        $widget = $this->createInstance($dependencies);
        $viewData = $widget->createViewData();

        $this->assertEquals([
            'parentKey' => 'parentData',
            'key' => 'data'
        ], $viewData);
    }

    public function testIsHiddenIsFromType()
    {
        $dependencies = $this->createDependencies();
        $dependencies->type = $this->createTypeMock(true);
        $widget = $this->createInstance($dependencies);

        $this->assertTrue($widget->isHidden());

        $dependencies->type = $this->createTypeMock(false);
        $widget = $this->createInstance($dependencies);

        $this->assertFalse($widget->isHidden());
    }

    public function testGetPermissionIsFromType()
    {
        $dependencies = $this->createDependencies();
        $dependencies->type = $this->createTypeMock(false, 'ROLE_TEST');
        $widget = $this->createInstance($dependencies);

        $this->assertEquals('ROLE_TEST', $widget->getPermission());
    }

    private function createDependencies()
    {
        $dependencies = new WidgetTestDependencies();
        $dependencies->type = null;
        $dependencies->parents = [];
        $dependencies->options = [];
        return $dependencies;
    }

    private function createInstance(WidgetTestDependencies $dependencies)
    {
        return new DashboardWidget($dependencies->type, $dependencies->parents, $dependencies->options);
    }

    /**
     * @return DashboardWidget|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createTypeMock(bool $hidden = false, string $role = 'ROLE_TEST', string $dataKey = 'key', string $data = 'data')
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getMockBuilder(AbstractDashboardWidgetType::class)->disableOriginalConstructor()->getMock();
        $mock->method('isHidden')->willReturn($hidden);
        $mock->method('getPermission')->willReturn($role);
        $mock->method('createViewData')->will(
            $this->returnCallback(
                function ($options, $viewData, $resource) use ($dataKey, $data) {
                    $viewData[$dataKey] = $data;
                }
            )
        );
        return $mock;
    }
}

class WidgetTestDependencies
{
    /** @var DashboardWidgetTypeInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $type;

    /** @var TypeInterface[]|\PHPUnit_Framework_MockObject_MockObject */
    public $parents;

    /** @var array */
    public $options;
}

class WidgetTestViewData
{
    /** @var array|\PHPUnit_Framework_MockObject_MockObject */
    public $data;
}
