<?php


namespace Enhavo\Bundle\MultiTenancyBundle\Tests\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ChainResolver;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use PHPUnit\Framework\TestCase;

class ChainResolverTest extends TestCase
{
    public function testResolver()
    {
        $tenantOne = $this->createMock(TenantInterface::class);
        $resolverMockOne = $this->createMock(ResolverInterface::class);
        $resolverMockOne->method('getTenant')->willReturn($tenantOne);

        $tenantTwo = $this->createMock(TenantInterface::class);
        $resolverMockTwo = $this->createMock(ResolverInterface::class);
        $resolverMockTwo->method('getTenant')->willReturn($tenantTwo);

        $resolver = new ChainResolver();
        $resolver->addResolver($resolverMockOne, 10);
        $resolver->addResolver($resolverMockTwo, 5);
        $this->assertTrue($resolver->getTenant() === $tenantOne);

        $resolver = new ChainResolver();
        $resolver->addResolver($resolverMockTwo, 5);
        $resolver->addResolver($resolverMockOne, 10);
        $this->assertTrue($resolver->getTenant() === $tenantOne);

        $resolver = new ChainResolver();
        $resolver->addResolver($resolverMockTwo);
        $resolver->addResolver($resolverMockOne);
        $this->assertTrue($resolver->getTenant() === $tenantTwo);
    }
}
