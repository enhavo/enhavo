<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Locale;

use Enhavo\Bundle\TranslationBundle\Locale\LocaleProviderInterface;
use Enhavo\Bundle\TranslationBundle\Locale\LocaleResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleResolverTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new LocaleResolverTestDependencies();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->getMock();
        $dependencies->request = $this->getMockBuilder(Request::class)->getMock();
        $dependencies->request->attributes = $this->getMockBuilder(ParameterBag::class)->getMock();
        $dependencies->localeProvider = $this->getMockBuilder(LocaleProviderInterface::class)->getMock();

        $dependencies->localeProvider->method('getLocales')->willReturn(['fr', 'es']);
        $dependencies->localeProvider->method('getDefaultLocale')->willReturn('fr');

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        $resolver = new LocaleResolver(
            $dependencies->requestStack,
            $dependencies->localeProvider
        );

        return $resolver;
    }

    public function testResolveSuccess()
    {
        $dependencies = $this->createDependencies();
        $resolver = $this->createInstance($dependencies);

        $dependencies->requestStack->method('getMainRequest')->willReturn($dependencies->request);
        $dependencies->request->attributes->method('get')->willReturnCallback(function ($property) {
            if ('_locale' === $property) {
                return 'es';
            }
        });

        $this->assertEquals('es', $resolver->resolve());
    }

    public function testResolveSet()
    {
        $dependencies = $this->createDependencies();
        $resolver = $this->createInstance($dependencies);

        $dependencies->requestStack->method('getMainRequest')->willReturn($dependencies->request);
        $dependencies->request->attributes->method('get')->willReturnCallback(function ($property) {
            if ('_locale' === $property) {
                return 'es';
            }
        });

        $this->assertEquals('es', $resolver->resolve());

        // test if it will not be resolved again
        $dependencies->request->attributes->method('get')->willReturnCallback(function ($property) {
            if ('_locale' === $property) {
                return 'fr';
            }
        });
        $this->assertEquals('es', $resolver->resolve());
    }
}

class LocaleResolverTestDependencies
{
    /** @var RequestStack|MockObject */
    public $requestStack;

    /** @var Request|MockObject */
    public $request;

    /** @var LocaleProviderInterface|MockObject */
    public $localeProvider;
}
