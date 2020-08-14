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
     * @var MetadataRepository
     */
    private $metadataRepository;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var EntityResolverInterface
     */
    private $entityResolver;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * RouteTranslator constructor.
     * @param MetadataRepository $metadataRepository
     * @param string $defaultLocale
     * @param EntityResolverInterface $entityResolver
     * @param EntityManagerInterface $em
     */
    public function __construct(MetadataRepository $metadataRepository, string $defaultLocale, EntityResolverInterface $entityResolver, EntityManagerInterface $em)
    {
        $this->metadataRepository = $metadataRepository;
        $this->defaultLocale = $defaultLocale;
        $this->entityResolver = $entityResolver;
        $this->em = $em;
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
                $this->getEntityManager()->remove($route);
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

        $this->getEntityManager()->persist($translationRoute);

        return $translationRoute;
    }

    private function deleteTranslationData($entity)
    {
        $repository = $this->getEntityManager()->getRepository(TranslationRoute::class);

        $translationRoutes = $repository->findTranslationRoutes(
            $this->entityResolver->getName($entity),
            $entity->getId()
        );

        foreach ($translationRoutes as $translationRoute) {
            $this->getEntityManager()->remove($translationRoute);
        }
    }

    private function findTranslationRoute($entity, $property, $locale): ?TranslationRoute
    {
        $repository = $this->getEntityManager()->getRepository(TranslationRoute::class);

        $translationRoute = $repository->findTranslationRoute(
            $this->entityResolver->getName($entity),
            $entity->getId(),
            $property,
            $locale
        );

        return $translationRoute;
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
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
