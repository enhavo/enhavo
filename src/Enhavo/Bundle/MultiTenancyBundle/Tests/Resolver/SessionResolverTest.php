<?php


namespace Enhavo\Bundle\MultiTenancyBundle\Tests\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\SessionResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionResolverTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new SessionResolverTestDependencies();
        $dependencies->provider = $this->createMock(ProviderInterface::class);
        $dependencies->requestStack = $this->createMock(RequestStack::class);
        $dependencies->request = $this->createMock(Request::class);
        $dependencies->session = $this->createMock(Session::class);
        $dependencies->request->method('getSession')->willReturn($dependencies->session);
        $dependencies->requestStack->method('getCurrentRequest')->willReturn($dependencies->request);
        return $dependencies;
    }

    private function createInstance(SessionResolverTestDependencies $dependencies, $routePrefixOnly = null)
    {
        return new SessionResolver(
            $dependencies->provider,
            $dependencies->requestStack,
            $routePrefixOnly
        );
    }

    public function testResolver()
    {
        $tenantOne = new Tenant();
        $tenantOne->setKey('one');
        $tenantTwo = new Tenant();
        $tenantTwo->setKey('two');

        $dependencies = $this->createDependencies();
        $dependencies->provider->expects($this->once())->method('getTenants')->willReturn([$tenantOne, $tenantTwo]);
        $dependencies->session->expects($this->once())->method('has')->willReturn(true);
        $dependencies->session->expects($this->once())->method('get')->willReturn('two');
        $resolver = $this->createInstance($dependencies);

        $this->assertEquals($tenantTwo, $resolver->getTenant());
    }

    public function testResolverNotFound()
    {
        $tenantOne = new Tenant();
        $tenantOne->setKey('one');
        $tenantTwo = new Tenant();
        $tenantTwo->setKey('two');

        $dependencies = $this->createDependencies();
        $dependencies->provider->expects($this->once())->method('getTenants')->willReturn([$tenantOne, $tenantTwo]);
        $dependencies->session->expects($this->once())->method('has')->willReturn(true);
        $dependencies->session->expects($this->once())->method('get')->willReturn('three');
        $resolver = $this->createInstance($dependencies);

        $this->assertNull($resolver->getTenant());
    }

    public function testResolverRoutePrefixOnly()
    {
        $tenantOne = new Tenant();
        $tenantOne->setKey('one');
        $tenantTwo = new Tenant();
        $tenantTwo->setKey('two');

        $dependencies = $this->createDependencies();
        $dependencies->provider->expects($this->never())->method('getTenants');
        $dependencies->request->expects($this->once())->method('getPathInfo')->willReturn('/user/profile');
        $dependencies->session->expects($this->never())->method('has');
        $dependencies->session->expects($this->never())->method('get');
        $resolver = $this->createInstance($dependencies, '/admin');

        $this->assertNull($resolver->getTenant());
    }

    public function testResolverNotSet()
    {
        $tenantOne = new Tenant();
        $tenantOne->setKey('one');
        $tenantTwo = new Tenant();
        $tenantTwo->setKey('two');

        $dependencies = $this->createDependencies();
        $dependencies->provider->expects($this->never())->method('getTenants');
        $dependencies->session->expects($this->once())->method('has')->willReturn(false);
        $dependencies->session->expects($this->never())->method('get');
        $resolver = $this->createInstance($dependencies);

        $this->assertNull($resolver->getTenant());
    }

}

class SessionResolverTestDependencies
{
    /** @var MockObject|ProviderInterface */
    public $provider;
    /** @var RequestStack|MockObject */
    public $requestStack;
    /** @var Request|MockObject */
    public $request;
    /** @var Session|MockObject */
    public $session;

}
