<?php
/**
 * @author blutze-media
 * @since 2020-11-23
 */
namespace Enhavo\Bundle\MultiTenancyBundle\Tests\Tenant;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TenantManagerTest extends TestCase
{
    private function createDependencies(): TenantManagerTestDependencies
    {
        $dependencies = new TenantManagerTestDependencies();
        $dependencies->resolver = $this->getMockBuilder(ResolverInterface::class)->getMock();
        $dependencies->provider = $this->getMockBuilder(ProviderInterface::class)->getMock();
        $dependencies->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance(TenantManagerTestDependencies $dependencies): TenantManager
    {
        return new TenantManager(
            $dependencies->resolver,
            $dependencies->provider,
            $dependencies->entityManager
        );
    }

    public function testGetTenant()
    {
        $tenantA = new Tenant();
        $tenantA->setKey('de_DE');
        $tenantB = new Tenant();
        $tenantB->setKey('de_GB');
        $tenants = [
            $tenantA,
            $tenantB,
        ];

        $dependencies = $this->createDependencies();
        $dependencies->provider->expects($this->exactly(2))->method('getTenants')->willReturn($tenants);
        $dependencies->resolver->expects($this->once())->method('getTenant')->willReturn($tenantB);
        $instance = $this->createInstance($dependencies);

        $tenant = $instance->getTenant('de_DE');

        $this->assertEquals($tenantA, $tenant);

        $tenant = $instance->getTenant('de_FR');

        $this->assertNull($tenant);

        $tenant = $instance->getTenant();

        $this->assertEquals($tenantB, $tenant);
    }

    public function testGetTenants()
    {
        $tenantA = new Tenant();
        $tenantA->setKey('de_DE');
        $tenantB = new Tenant();
        $tenantB->setKey('de_GB');
        $tenants = [
            $tenantA,
            $tenantB,
        ];

        $dependencies = $this->createDependencies();
        $dependencies->provider->expects($this->exactly(1))->method('getTenants')->willReturn($tenants);
        $instance = $this->createInstance($dependencies);

        $result = $instance->getTenants();

        $this->assertEquals($tenants, $result);
    }
}

class TenantManagerTestDependencies
{
    /** @var ResolverInterface|MockObject */
    public $resolver;
    /** @var ProviderInterface|MockObject */
    public $provider;
    /** @var EntityManagerInterface|MockObject */
    public $entityManager;
}
