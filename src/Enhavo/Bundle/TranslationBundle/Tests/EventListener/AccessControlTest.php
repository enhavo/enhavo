<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\EventListener;


use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\TranslationBundle\EventListener\AccessControl;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AccessControlTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new AccessControlTestDependencies();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->getMock();
        $dependencies->request = $this->getMockBuilder(Request::class)->getMock();
        $dependencies->request->attributes = $this->getMockBuilder(ParameterBag::class)->getMock();
        $dependencies->localeResolver = $this->getMockBuilder(LocaleResolverInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance($dependencies, array $accessRegex = ['#^(?!/admin/).*#'])
    {
        $accessControl = new AccessControl(
            $dependencies->requestStack,
            $dependencies->localeResolver,
            $accessRegex
        );

        return $accessControl;
    }

    public function testIsAccessFalse()
    {
        $dependencies = $this->createDependencies();
        $control = $this->createInstance($dependencies);

        $dependencies->requestStack->expects($this->exactly(1))->method('getMainRequest')->willReturn($dependencies->request);
        $dependencies->request->expects($this->once())->method('getPathInfo')->willReturn('/admin/enhavo/page/page/update/3?view_id=4');

        $this->assertFalse($control->isAccess());
    }

    public function testIsAccessTrue()
    {
        $dependencies = $this->createDependencies();
        $control = $this->createInstance($dependencies);

        $dependencies->requestStack->expects($this->once())->method('getMainRequest')->willReturn($dependencies->request);
        $dependencies->request->expects($this->once())->method('getPathInfo')->willReturn('/my/page/10');

        $this->assertTrue($control->isAccess());
    }

    public function testGetLocale()
    {
        $dependencies = $this->createDependencies();
        $control = $this->createInstance($dependencies);

        $dependencies->localeResolver->expects($this->once())->method('resolve')->willReturn('_locale');

        $this->assertEquals('_locale', $control->getLocale());
    }

    public function testGetLocaleTwice()
    {
        $dependencies = $this->createDependencies();
        $control = $this->createInstance($dependencies);

        $localeDelivered = false;

        $dependencies->localeResolver->expects($this->once())->method('resolve')->willReturnCallback(function () use ($localeDelivered) {
            if (false == $localeDelivered) {
                return '_locale';
            }

            return '__locale';
        });

        $this->assertEquals('_locale', $control->getLocale());
        $this->assertNotEquals('__locale', $control->getLocale());
    }

    public function testIsAccessFalseNoMainRequestTwice()
    {
        $dependencies = $this->createDependencies();
        $control = $this->createInstance($dependencies);

        $dependencies->requestStack->expects($this->once())->method('getMainRequest')->willReturn(null);

        $this->assertFalse($control->isAccess());
        $this->assertFalse($control->isAccess());
    }
}

class AccessControlTestDependencies
{
    /** @var RequestStack|MockObject */
    public $requestStack;

    /** @var LocaleResolverInterface|MockObject */
    public $localeResolver;

    /** @var Request|MockObject */
    public $request;
}
