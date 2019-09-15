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
use Enhavo\Component\Metadata\MetadataRepository;

class RouteTranslator
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
        $this->checkEntity($entity);

        if($locale == $this->defaultLocale) {
            return;
        }

        $translationRoute = $this->findTranslationRoute($entity, $property, $locale);
        if($translationRoute === null) {
            $this->createTranslationRoute($entity, $property, $locale, $value);
        } else {
            if($translationRoute->getRoute() !== $value) {
                $route = $translationRoute->getRoute();
                $this->getEntityManager()->remove($route);
                $translationRoute->setRoute($value);
            }
        }
    }

    public function getTranslation($entity, $property, $locale): ?RouteInterface
    {
        $this->checkEntity($entity);

        if($locale == $this->defaultLocale) {
            return null;
        }

        $translationRoute = $this->findTranslationRoute($entity, $property, $locale);

        if($translationRoute !== null) {
            return $translationRoute->getRoute();
        }

        return null;
    }

    public function delete($entity)
    {
        $this->deleteTranslationData($entity);
    }

    private function checkEntity($entity)
    {
        if(!$this->metadataRepository->hasMetadata($entity)) {
            throw new TranslationException(sprintf('Entity "%s" is not translatable', get_class($entity)));
        }
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

        foreach($translationRoutes as $translationRoute) {
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
}
