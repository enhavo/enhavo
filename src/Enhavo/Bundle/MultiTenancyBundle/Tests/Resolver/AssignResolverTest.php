<?php


namespace Enhavo\Bundle\MultiTenancyBundle\Tests\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\AssignResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AssignResolverTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new AssignResolverTestDependencies();
        $dependencies->provider = $this->createMock(ProviderInterface::class);

        return $dependencies;
    }

    private function createInstance(AssignResolverTestDependencies $dependencies, $initialAssigned = null)
    {
        return new AssignResolver($dependencies->provider, $initialAssigned);
    }

    public function testGetTenant()
    {
        $tenantOne = new Tenant();
        $tenantOne->setKey('one');
        $tenantOne->setDomains(['www.test.com']);

        $tenantTwo = new Tenant();
        $tenantTwo->setKey('two');
        $tenantTwo->setDomains(['www.foobar.com']);

        $dependencies = $this->createDependencies();
        $dependencies->provider->method('getTenants')->willReturn([$tenantOne, $tenantTwo]);

        $resolver = $this->createInstance($dependencies);

        $this->assertNull($resolver->getTenant());

        $resolver->assign('two');
        $this->assertEquals($tenantTwo, $resolver->getTenant());

        $resolver->assign('three');
        $this->assertNull($resolver->getTenant());

        $resolver = $this->createInstance($dependencies, 'one');
        $this->assertEquals($tenantOne, $resolver->getTenant());
    }

}

class AssignResolverTestDependencies
{
    /** @var ProviderInterface|MockObject */
    public $provider;
}
