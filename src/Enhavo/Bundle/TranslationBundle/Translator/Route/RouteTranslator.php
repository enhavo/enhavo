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
use Enhavo\Bundle\TranslationBundle\Entity\TranslationRoute;
use Enhavo\Bundle\TranslationBundle\Translator\DataMap;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
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
     * @var DataMap
     */
    private $buffer;

    /**
     * @var DataMap
     */
    private $originalData;

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

        $this->buffer = new DataMap();
        $this->originalData = new DataMap();
    }

    public function setTranslation($entity, $property, $locale, $value): void
    {
        if ($locale == $this->defaultLocale) {
            return;
        }

        $translationRoute = $this->buffer->load($entity, $property, $locale)
            ?? $this->load($entity, $property, $locale);

        if ($translationRoute === null) {
            $translationRoute = $this->createTranslationRoute($entity, $property, $locale, $value);
        } else {
            if ($translationRoute->getRoute() !== $value) {
                $route = $translationRoute->getRoute();
                $this->entityManager->remove($route);
                $translationRoute->setRoute($value);
            }
        }

        $this->buffer->store($entity, $property, $locale, $translationRoute);
    }

    public function getTranslation($entity, $property, $locale): ?RouteInterface
    {
        if ($locale == $this->defaultLocale) {
            return null;
        }

        $translationRoute = $this->buffer->load($entity, $property, $locale);
        if ($translationRoute !== null) {
            return $translationRoute->getRoute();
        }

        $translationRoute = $this->load($entity, $property, $locale);
        if ($translationRoute !== null) {
            $this->buffer->store($entity, $property, $locale, $translationRoute);
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

    private function load($entity, $property, $locale): ?TranslationRoute
    {
        $repository = $this->getRepository();

        return $repository->findTranslationRoute(
            $this->entityResolver->getName($entity),
            $entity->getId(),
            $property,
            $locale
        );
    }

    private function getRepository()
    {
        return $this->entityManager->getRepository(TranslationRoute::class);
    }

    public function translate($entity, string $property, string $locale, array $options)
    {
        // translation data is stored inside the object
        if ($locale === $this->defaultLocale) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $newValue = $this->getTranslation($entity, $property, $locale);
        $oldValue = $accessor->getValue($entity, $property);
        $this->originalData->store($entity, $property, null, $oldValue);

        if ($newValue !== null || $options['allow_null']) {
            $accessor->setValue($entity, $property, $newValue);
        }
    }

    public function detach($entity, string $property, string $locale, array $options)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $originalValue = $this->originalData->load($entity, $property, null);
        $translationValue = $accessor->getValue($entity, $property);
        $this->setTranslation($entity, $property, $locale, $translationValue);
        $accessor->setValue($entity, $property, $originalValue);

        $this->originalData->delete($entity);
    }
}
