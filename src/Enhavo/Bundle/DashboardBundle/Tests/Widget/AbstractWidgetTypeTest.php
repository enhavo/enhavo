<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Widget;


use Enhavo\Bundle\DashboardBundle\Widget\AbstractWidgetType;
use Enhavo\Bundle\DashboardBundle\Widget\Type\WidgetType;
use Enhavo\Bundle\DashboardBundle\Widget\WidgetTypeInterface;
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

        $this->assertEquals(WidgetType::class, $instance->getParentType());
    }

    public function testConfigureOptionsReturns()
    {
        $instance = $this->createAbstractWidgetType();

        $this->assertNull($instance->configureOptions(new OptionsResolver()));
    }

    private function createAbstractWidgetType()
    {
        return $this->getMockBuilder(AbstractWidgetType::class)->getMockForAbstractClass();
    }

    /**
     * @return WidgetTypeInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createTypeMock(bool $hidden = false, string $role = 'ROLE_TEST')
    {
        $mock = $this->createMock(WidgetTypeInterface::class);
        $mock->method('isHidden')->willReturn($hidden);
        $mock->method('getPermission')->willReturn($role);
        $mock->method('getParentType')->willReturn(WidgetType::class);

        return $mock;
    }
}
