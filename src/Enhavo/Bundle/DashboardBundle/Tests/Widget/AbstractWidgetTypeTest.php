<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Widget;


use Enhavo\Bundle\DashboardBundle\Dashboard\AbstractDashboardWidgetType;
use Enhavo\Bundle\DashboardBundle\Dashboard\Type\BaseDashboardWidgetType;
use Enhavo\Bundle\DashboardBundle\Dashboard\DashboardWidgetTypeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractWidgetTypeTest extends TestCase
{
    public function testPermission()
    {
        $instance = $this->createAbstractWidgetType();
        $instance->setParent($this->createTypeMock());

        $this->assertEquals('ROLE_TEST', $instance->getPermission([]));
    }

    public function testIsHiddenValueFromParent()
    {
        $instance = $this->createAbstractWidgetType();

        $instance->setParent($this->createTypeMock(true));
        $this->assertTrue($instance->isHidden([]));

        $instance->setParent($this->createTypeMock(false));
        $this->assertFalse($instance->isHidden([]));
    }

    public function testGetParentTypeIsWidgetType()
    {
        $instance = $this->createAbstractWidgetType();

        $this->assertEquals(BaseDashboardWidgetType::class, $instance->getParentType());
    }

    public function testConfigureOptionsReturns()
    {
        $instance = $this->createAbstractWidgetType();

        $this->assertNull($instance->configureOptions(new OptionsResolver()));
    }

    private function createAbstractWidgetType()
    {
        return $this->getMockBuilder(AbstractDashboardWidgetType::class)->getMockForAbstractClass();
    }

    /**
     * @return DashboardWidgetTypeInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createTypeMock(bool $hidden = false, string $role = 'ROLE_TEST')
    {
        $mock = $this->createMock(DashboardWidgetTypeInterface::class);
        $mock->method('isHidden')->willReturn($hidden);
        $mock->method('getPermission')->willReturn($role);
        $mock->method('getParentType')->willReturn(BaseDashboardWidgetType::class);

        return $mock;
    }
}
