<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translator\Text;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationRoute;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRepository;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRouteRepository;
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

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        $translator = new RouteTranslator(
            $dependencies->entityManager,
            $dependencies->entityResolver,
            'es'
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
        $route = $this->getMockBuilder(RouteInterface::class)->getMock();
        /** @var RouteInterface|MockObject $route */
        $route2 = $this->getMockBuilder(RouteInterface::class)->getMock();

        $dependencies->repository->method('findTranslationRoute')->willReturnCallback(function ($entity, $property, $locale) use ($route) {
            $translation = new TranslationRoute();
            $translation->setRoute($route);

            return $translation;
        });

        $entity = new TranslatableMock();

        $translator->setTranslation($entity, 'route', 'fr', $route2);

        $this->assertEquals($route2, $translator->getTranslation($entity, 'route', 'fr'));

    }

    public function testGetTranslationDefaultLocale()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        $entity = new TranslatableMock();

        $this->assertNull($translator->getTranslation($entity, 'route', 'es'));
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



}

class RouteTranslatorTestDependencies
{
    /** @var EntityManagerInterface|MockObject */
    public $entityManager;

    /** @var EntityResolverInterface|MockObject */
    public $entityResolver;

    /** @var TranslationRepository|MockObject */
    public $repository;
}
