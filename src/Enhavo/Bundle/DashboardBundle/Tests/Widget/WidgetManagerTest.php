<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Widget;


use Enhavo\Bundle\DashboardBundle\Widget\Widget;
use Enhavo\Bundle\DashboardBundle\Widget\WidgetManager;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class WidgetManagerTest extends TestCase
{
    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->configurations = [
            'key1' => [
                'type' => 'test1',
            ],
            'key2' => [
                'type' => 'test2',
            ],
        ];

        $dependencies->factory->method('create')
            ->willReturnOnConsecutiveCalls(
                $this->createWidgetMock(false, 'ROLE_TEST', 12),
                $this->createWidgetMock(false, 'ROLE_TEST', 123)
            );
        $dependencies->checker->method('isGranted')->willReturn(true);

        $manager = $this->createInstance($dependencies);
        $data = $manager->createViewData();

        $this->assertEquals([
            [
                'value' => 12
            ],
            [
                'value' => 123
            ]
        ], $data);
    }

    public function testHidden()
    {
        $dependencies = $this->createHiddenTestDependencies(true);
        $manager = $this->createInstance($dependencies);
        $data = $manager->createViewData();

        $this->assertCount(0, $data);

        $dependencies = $this->createHiddenTestDependencies(false);
        $manager = $this->createInstance($dependencies);
        $data = $manager->createViewData();

        $this->assertCount(1, $data);
    }

    private function createHiddenTestDependencies($hidden = false)
    {
        $dependencies = $this->createDependencies();
        $dependencies->configurations = [
            'key' => [
                'type' => 'test',
            ],
        ];
        $dependencies->factory->method('create')->willReturn($this->createWidgetMock($hidden));
        $dependencies->checker->method('isGranted')->willReturn(true);

        return $dependencies;
    }

    public function testPermission()
    {
        $dependencies = $this->createDependencies();
        $dependencies->configurations = [
            'key' => [
                'type' => 'test',
            ],
        ];
        $dependencies->factory->method('create')->willReturn($this->createWidgetMock());
        $dependencies->checker->method('isGranted')->willReturn(false);
        $manager = $this->createInstance($dependencies);
        $data = $manager->createViewData();

        $this->assertCount(0, $data);
    }

    private function createInstance(WidgetManagerTestDependencies $dependencies)
    {
        return new WidgetManager($dependencies->factory, $dependencies->checker, $dependencies->configurations);
    }

    private function createDependencies()
    {
        $dependencies = new WidgetManagerTestDependencies();
        $dependencies->factory = $this->getMockBuilder(FactoryInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->checker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->getMock();
        $dependencies->configurations = [];
        return $dependencies;
    }

    /**
     * @return Widget|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createWidgetMock(bool $hidden = false, string $role = 'ROLE_TEST', int $value = 0)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(Widget::class);
        $mock->method('isHidden')->willReturn($hidden);
        $mock->method('getPermission')->willReturn($role);
        $mock->method('createViewData')->willReturn([
            'value' => $value
        ]);
        return $mock;
    }
}

class WidgetManagerTestDependencies
{
    /** @var FactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $factory;

    /** @var AuthorizationChecker|\PHPUnit_Framework_MockObject_MockObject */
    public $checker;

    /** @var array */
    public $configurations;
}
