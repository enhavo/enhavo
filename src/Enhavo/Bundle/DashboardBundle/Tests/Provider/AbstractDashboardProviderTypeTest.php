<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Provider;


use Enhavo\Bundle\DashboardBundle\Provider\AbstractDashboardProviderType;
use Enhavo\Bundle\DashboardBundle\Provider\Type\DashboardProviderType;
use PHPUnit\Framework\TestCase;

class AbstractDashboardProviderTypeTest extends TestCase
{
    public function testGetParentTypeIsDashboardProviderType()
    {
        $mock = $this->getMockForAbstractClass(AbstractDashboardProviderType::class);

        $this->assertEquals(DashboardProviderType::class, $mock->getParentType());
    }
}
