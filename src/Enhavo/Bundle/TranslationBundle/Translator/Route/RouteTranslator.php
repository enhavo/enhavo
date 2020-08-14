<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-30
 * Time: 00:46
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Route;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\TranslationBundle\Exception\TranslationException;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationRoute;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Translator\AbstractTranslator;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use Enhavo\Component\Metadata\MetadataRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;

class RouteTranslator implements TranslatorInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EntityResolverInterface
     */
    private $entityResolver;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * RouteTranslator constructor.
     * @param EntityManagerInterface $entityManager
     * @param EntityResolverInterface $entityResolver
     * @param string $defaultLocale
     */
    public function __construct(EntityManagerInterface $entityManager, EntityResolverInterface $entityResolver, string $defaultLocale)
    {
        $this->entityManager = $entityManager;
        $this->entityResolver = $entityResolver;
        $this->defaultLocale = $defaultLocale;
    }


    public function setTranslation($entity, $property, $locale, $value): void
    {
        if ($locale == $this->defaultLocale) {
            return;
        }

        $translationRoute = $this->findTranslationRoute($entity, $property, $locale);
        if ($translationRoute === null) {
            $this->createTranslationRoute($entity, $property, $locale, $value);
        } else {
            if ($translationRoute->getRoute() !== $value) {
                $route = $translationRoute->getRoute();
                $this->entityManager->remove($route);
                $translationRoute->setRoute($value);
            }
        }
    }

    public function getTranslation($entity, $property, $locale): ?RouteInterface
    {
        if ($locale == $this->defaultLocale) {
            return null;
        }

        $translationRoute = $this->findTranslationRoute($entity, $property, $locale);

        if ($translationRoute !== null) {
            return $translationRoute->getRoute();
        }

        return null;
    }

    public function delete($entity, string $property)
    {
        $this->deleteTranslationData($entity);
    }

    private function createTranslationRoute($entity, $property, $locale, RouteInterface $route): TranslationRoute
    {
        $route->setContent($entity);

        $translationRoute = new TranslationRoute();
        $translationRoute->setLocale($locale);
        $translationRoute->setProperty($property);
        $translationRoute->setRoute($route);

        $this->entityManager->persist($translationRoute);

        return $translationRoute;
    }

    private function deleteTranslationData($entity)
    {
        $repository = $this->getRepository();

        $translationRoutes = $repository->findTranslationRoutes(
            $this->entityResolver->getName($entity),
            $entity->getId()
        );

        foreach ($translationRoutes as $translationRoute) {
            $this->entityManager->remove($translationRoute);
        }
    }

    private function findTranslationRoute($entity, $property, $locale): ?TranslationRoute
    {
        $repository = $this->getRepository();

        $translationRoute = $repository->findTranslationRoute(
            $this->entityResolver->getName($entity),
            $entity->getId(),
            $property,
            $locale
        );

        return $translationRoute;
    }

    private function getRepository()
    {
        return $this->entityManager->getRepository(TranslationRoute::class);
    }

    public function translate($entity, string $property, string $locale, array $options)
    {
        // TODO: Implement translate() method.
    }

    public function detach($entity, string $property, string $locale, array $options)
    {
        // TODO: Implement detach() method.
    }


    public function getAcceptedTypes(): array
    {
        return ['route'];
    }
}
