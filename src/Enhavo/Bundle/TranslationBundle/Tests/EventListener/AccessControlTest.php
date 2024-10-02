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
        $dependencies->requestStack->method('getMainRequest')->willReturn($dependencies->request);
        $dependencies->localeResolver = $this->getMockBuilder(LocaleResolverInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance($dependencies, array $accessRegex = ['#^(?!/admin/).*#'], bool $defaultAccess = true)
    {
        $accessControl = new AccessControl(
            $dependencies->requestStack,
            $accessRegex,
            $defaultAccess
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

        $dependencies->requestStack->expects($this->exactly(1))->method('getMainRequest')->willReturn($dependencies->request);
        $dependencies->request->expects($this->once())->method('getPathInfo')->willReturn('/my/page/10');

        $this->assertTrue($control->isAccess());
    }

    public function testDefaultAccessFalseIsFalse()
    {
        $dependencies = $this->createDependencies();
        $control = $this->createInstance($dependencies, [
            '#^/admin/enhavo/.*#',
        ], false);

        $dependencies->requestStack->expects($this->exactly(1))->method('getMainRequest')->willReturn($dependencies->request);
        $dependencies->request->expects($this->once())->method('getPathInfo')->willReturn('/admin/custom/10');

        $this->assertFalse($control->isAccess());
    }

    public function testDefaultAccessFalseIsTrue()
    {
        $dependencies = $this->createDependencies();
        $control = $this->createInstance($dependencies, [
            '#^/admin/enhavo/.*#',
        ], false);

        $dependencies->requestStack->expects($this->exactly(1))->method('getMainRequest')->willReturn($dependencies->request);
        $dependencies->request->expects($this->once())->method('getPathInfo')->willReturn('/admin/enhavo/10');

        $this->assertTrue($control->isAccess());
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
