<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Widget;


use Enhavo\Bundle\DashboardBundle\Widget\AbstractWidgetType;
use Enhavo\Bundle\DashboardBundle\Widget\Type\WidgetType;
use Enhavo\Bundle\DashboardBundle\Widget\Widget;
use Enhavo\Bundle\DashboardBundle\Widget\WidgetTypeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractWidgetTypeTest extends TestCase
{
    /**
     * @var AbstractWidgetType
     */
    private $instance;

    public function setUp()
    {
        $this->instance = $this->createAbstractWidgetType();
    }

    public function testPermission()
    {
        $this->instance->setParent($this->createTypeMock());

        $this->assertEquals('ROLE_TEST', $this->instance->getPermission([]));
    }

    public function testIsHiddenValueFromParent()
    {
        $this->instance->setParent($this->createTypeMock(true));
        $this->assertTrue($this->instance->isHidden([]));

        $this->instance->setParent($this->createTypeMock(false));
        $this->assertFalse($this->instance->isHidden([]));
    }

    public function testGetParentTypeIsWidgetType()
    {
        $this->assertEquals(WidgetType::class, $this->instance->getParentType());
    }

    public function testConfigureOptionsReturns()
    {
        $this->assertNull($this->instance->configureOptions(new OptionsResolver()));
    }

    private function createAbstractWidgetType()
    {
        return $this->getMockBuilder(AbstractWidgetType::class)->getMockForAbstractClass();
    }

    /**
     * @return Widget|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createTypeMock(bool $hidden = false, string $role = 'ROLE_TEST')
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getMockBuilder(WidgetTypeInterface::class)->disableOriginalConstructor()->getMock();
        $mock->method('isHidden')->willReturn($hidden);
        $mock->method('getPermission')->willReturn($role);
        $mock->method('getParentType')->willReturn(WidgetType::class);

        return $mock;
    }
}
