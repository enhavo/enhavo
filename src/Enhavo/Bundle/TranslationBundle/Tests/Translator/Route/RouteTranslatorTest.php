<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Translator\Text;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationRoute;
use Enhavo\Bundle\TranslationBundle\Locale\LocaleProviderInterface;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRepository;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRouteRepository;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\RouteableMock;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\TranslatableMock;
use Enhavo\Bundle\TranslationBundle\Translator\Route\RouteTranslator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RouteTranslatorTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new RouteTranslatorTestDependencies();
        $dependencies->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->repository = $this->getMockBuilder(TranslationRouteRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->entityManager->method('getRepository')->willReturn($dependencies->repository);
        $dependencies->entityResolver = $this->getMockBuilder(EntityResolverInterface::class)->getMock();
        $dependencies->localeProvider = $this->getMockBuilder(LocaleProviderInterface::class)->getMock();
        $dependencies->localeProvider->method('getDefaultLocale')->willReturn('es');

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        $translator = new RouteTranslator(
            $dependencies->entityManager,
            $dependencies->entityResolver,
            $dependencies->localeProvider
        );

        return $translator;
    }

    public function testSetTranslation()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        /** @var RouteInterface|MockObject $route */
        $route = $this->getMockBuilder(RouteInterface::class)->getMock();

        $entity = new TranslatableMock();

        $dependencies->repository->method('findTranslationRoute')->willReturnCallback(function ($class, $id, $property, $locale) use ($route) {
            if (null === $id) {
                return null;
            }
            $translation = new TranslationRoute();
            $translation->setRoute($route);

            return $translation;
        });

        $translator->setTranslation($entity, 'route', 'fr', $route);
        $entity->id = 1;

        $this->assertEquals($route, $translator->getTranslation($entity, 'route', 'fr'));
    }

    public function testResetTranslation()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        /** @var RouteInterface|MockObject $route */
        $route = new Route();
        $route->setName('route-a');
        /** @var RouteInterface|MockObject $route */
        $route2 = new Route();
        $route2->setName('route-b');

        $dependencies->repository->method('findTranslationRoute')->willReturnCallback(function ($entity, $property, $locale) use ($route) {
            $translation = new TranslationRoute();
            $translation->setRoute($route);

            return $translation;
        });

        $entity = new TranslatableMock();

        $translator->setTranslation($entity, 'route', 'fr', $route2);

        $this->assertEquals($route2, $translator->getTranslation($entity, 'route', 'fr'));
    }

    public function testGetSetTranslationDefaultLocale()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        $entity = new TranslatableMock();

        $this->assertNull($translator->getTranslation($entity, 'route', 'es'));
        $this->assertNull($translator->setTranslation($entity, 'route', 'es', []));
    }

    public function testGetExistingTranslation()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);
        /** @var RouteInterface|MockObject $route */
        $route = $this->getMockBuilder(RouteInterface::class)->getMock();

        $dependencies->repository->method('findTranslationRoute')->willReturnCallback(function ($entity, $property, $locale) use ($route) {
            $translation = new TranslationRoute();
            $translation->setRoute($route);

            return $translation;
        });

        $entity = new TranslatableMock();

        $this->assertEquals($route,
            $translator->getTranslation($entity, 'route', 'fr')
        );
    }

    public function testGetTranslationMissing()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        $entity = new TranslatableMock();

        $this->assertNull($translator->getTranslation($entity, 'route', 'fr'));
    }

    public function testDelete()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        $translation = new TranslationRoute();

        $dependencies->repository->expects($this->once())->method('findTranslationRoutes')->willReturnCallback(function () use ($translation) {
            return [$translation];
        });
        $dependencies->entityManager->expects($this->once())->method('remove');

        $entity = new TranslatableMock();

        $translator->delete($entity, 'route');
    }

    public function testTranslate()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        /** @var RouteInterface|MockObject $route */
        $route = $this->getMockBuilder(RouteInterface::class)->getMock();

        $entity = new RouteableMock();
        $entity->setRoute($route);

        $translator->translate($entity, 'route', 'en', ['allow_null' => true]);
        $this->assertNotEquals($route, $entity->getRoute());
    }

    public function testDetach()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        /** @var RouteInterface|MockObject $route */
        $route = new Route();
        $route->setName('route');
        /** @var RouteInterface|MockObject $route */
        $routeEn = new Route();
        $routeEn->setName('route-en');

        $entity = new RouteableMock();
        $entity->setName('routed');
        $entity->setRoute($route);

        $translator->setTranslation($entity, 'route', 'en', $routeEn);

        $translator->translate($entity, 'route', 'en', ['allow_null' => false]);
        $translator->detach($entity, 'route', 'en', []);

        $this->assertEquals($route, $entity->getRoute());

        $translator->translate($entity, 'route', 'es', ['allow_null' => false]);
        $this->assertEquals($route, $entity->getRoute());
    }
}

class RouteTranslatorTestDependencies
{
    /** @var EntityManagerInterface|MockObject */
    public $entityManager;

    /** @var EntityResolverInterface|MockObject */
    public $entityResolver;

    /** @var TranslationRepository|MockObject */
    public $repository;

    /** @var LocaleProviderInterface|MockObject */
    public $localeProvider;
}
