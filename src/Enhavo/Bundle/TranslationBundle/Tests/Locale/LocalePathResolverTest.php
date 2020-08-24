<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Locale;


use Enhavo\Bundle\TranslationBundle\Locale\LocalePathResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class LocalePathResolverTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new LocalePathResolverTestDependencies();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->getMock();
        $dependencies->request = $this->getMockBuilder(Request::class)->getMock();

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        $resolver = new LocalePathResolver(
            $dependencies->requestStack,
            ['fr', 'es'],
            'fr'
        );

        return $resolver;
    }

    public function testResolveSuccess()
    {
        $dependencies = $this->createDependencies();
        $resolver = $this->createInstance($dependencies);

        $dependencies->requestStack->method('getMasterRequest')->willReturn($dependencies->request);
        $dependencies->request->method('getPathInfo')->willReturn('/es/action/id');

        $this->assertEquals('es', $resolver->resolve());
    }

    public function testResolveFallback()
    {
        $dependencies = $this->createDependencies();
        $resolver = $this->createInstance($dependencies);

        $dependencies->requestStack->method('getMasterRequest')->willReturn($dependencies->request);
        $dependencies->request->method('getPathInfo')->willReturn('/en/action/id');

        $this->assertEquals('fr', $resolver->resolve());
    }

    public function testResolveRepeat()
    {
        $dependencies = $this->createDependencies();
        $resolver = $this->createInstance($dependencies);

        $dependencies->requestStack->method('getMasterRequest')->willReturn($dependencies->request);
        $dependencies->request->method('getPathInfo')->willReturn('/es/action/id');

        $this->assertEquals('es', $resolver->resolve());

        // test if it will not be resolved again
        $dependencies->request->method('getPathInfo')->willReturn('/en/action/id');
        $this->assertEquals('es', $resolver->resolve());
    }

}

class LocalePathResolverTestDependencies
{
    /** @var RequestStack|MockObject */
    public $requestStack;

    /** @var Request|MockObject */
    public $request;
}
