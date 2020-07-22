<?php


namespace Enhavo\Bundle\MultiTenancyBundle\Tests\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\HostResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class HostResolverTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new HostResolverDependencies();
        $dependencies->provider = $this->createMock(ProviderInterface::class);
        $dependencies->requestStack = $this->createMock(RequestStack::class);
        $dependencies->request = $this->createMock(Request::class);
        return $dependencies;
    }

    private function createInstance(HostResolverDependencies $dependencies)
    {
        return new HostResolver($dependencies->provider, $dependencies->requestStack);
    }

    public function testResolverWithHost()
    {
        $tenantOne = new Tenant();
        $tenantOne->setKey('one');
        $tenantOne->setDomains(['www.test.com']);

        $tenantTwo = new Tenant();
        $tenantTwo->setKey('two');
        $tenantTwo->setDomains(['www.foobar.com']);

        $dependencies = $this->createDependencies();
        $dependencies->provider->method('getTenants')->willReturn([$tenantOne, $tenantTwo]);
        $dependencies->request->method('getHost')->willReturn('www.test.com');
        $dependencies->requestStack->method('getCurrentRequest')->willReturn($dependencies->request);

        $resolver = $this->createInstance($dependencies);

        $this->assertTrue($resolver->getTenant() === $tenantOne);
    }

    public function testResolverNoMatch()
    {
        $tenantOne = new Tenant();
        $tenantOne->setKey('one');
        $tenantOne->setDomains(['www.test.com']);

        $dependencies = $this->createDependencies();
        $dependencies->provider->method('getTenants')->willReturn([$tenantOne]);
        $dependencies->request->method('getHost')->willReturn('www.something.com');
        $dependencies->requestStack->method('getCurrentRequest')->willReturn($dependencies->request);

        $resolver = $this->createInstance($dependencies);

        $this->assertNull($resolver->getTenant());
    }

    public function testResolverWithNoRequest()
    {
        $dependencies = $this->createDependencies();
        $dependencies->provider->method('getTenants')->willReturn([]);
        $dependencies->request->method('getHost')->willReturn('www.test.com');
        $dependencies->requestStack->method('getCurrentRequest')->willReturn(null);

        $resolver = $this->createInstance($dependencies);

        $this->assertNull($resolver->getTenant());
    }
}

class HostResolverDependencies
{
    /** @var ProviderInterface|MockObject */
    public $provider;

    /** @var RequestStack|MockObject */
    public $requestStack;

    /** @var Request|MockObject */
    public $request;
}
