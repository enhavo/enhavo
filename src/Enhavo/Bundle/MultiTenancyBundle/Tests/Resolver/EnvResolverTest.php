<?php


namespace Enhavo\Bundle\MultiTenancyBundle\Tests\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Exception\ResolveException;
use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\EnvResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EnvResolverTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new EnvResolverDependencies();
        $dependencies->provider = $this->createMock(ProviderInterface::class);
        return $dependencies;
    }

    private function createInstance(EnvResolverDependencies $dependencies)
    {
        return new EnvResolver($dependencies->provider, $dependencies->envName);
    }

    public function testResolverWithEnv()
    {
        $tenantOne = new Tenant();
        $tenantOne->setKey('one');
        $tenantTwo = new Tenant();
        $tenantTwo->setKey('two');

        $dependencies = $this->createDependencies();
        $dependencies->provider->method('getTenants')->willReturn([$tenantOne, $tenantTwo]);

        $resolver = $this->createInstance($dependencies);

        putenv('TENANCY_RANDOM_NAME=two');
        $this->assertTrue($resolver->getTenant() === $tenantTwo);
    }

    public function testResolverEnvNotSet()
    {
        $tenantOne = new Tenant();
        $tenantOne->setKey('one');

        $dependencies = $this->createDependencies();
        $dependencies->provider->method('getTenants')->willReturn([$tenantOne]);

        putenv('TENANCY_RANDOM_NAME=');
        $resolver = $this->createInstance($dependencies);
        $this->assertNull($resolver->getTenant());
    }

    public function testResolverNotFoundButEnvSet()
    {
        $tenantOne = new Tenant();
        $tenantOne->setKey('one');

        $dependencies = $this->createDependencies();
        $dependencies->provider->method('getTenants')->willReturn([$tenantOne]);

        $resolver = $this->createInstance($dependencies);

        putenv('TENANCY_RANDOM_NAME=something');
        $this->expectException(ResolveException::class);
        $resolver->getTenant();
    }
}

class EnvResolverDependencies
{
    /** @var MockObject|ProviderInterface */
    public $provider;
    public $envName = 'TENANCY_RANDOM_NAME';
}
