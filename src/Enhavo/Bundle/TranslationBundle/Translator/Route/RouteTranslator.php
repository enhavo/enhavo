<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Route;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationRoute;
use Enhavo\Bundle\TranslationBundle\Translator\AbstractTranslator;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class RouteTranslator
 */
class RouteTranslator extends AbstractTranslator
{
    public function delete($entity, string $property): void
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

    public function translate($entity, string $property, string $locale, array $options)
    {
        // translation data is stored inside the object
        if ($locale === $this->localeProvider->getDefaultLocale()) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $newValue = $this->getTranslation($entity, $property, $locale);
        $oldValue = $accessor->getValue($entity, $property);
        $this->originalData->store($entity, $property, null, $oldValue);

        if (null !== $newValue || $options['allow_null']) {
            $accessor->setValue($entity, $property, $newValue);
        }
    }

    public function detach($entity, string $property, string $locale, array $options)
    {
        // translation data is stored inside the object
        if ($locale === $this->localeProvider->getDefaultLocale()) {
            return;
        }

        parent::detach($entity, $property, $locale, $options);
    }

    protected function createTranslation($entity, $property, $locale, $value): ?object
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof RouteInterface) {
            throw new \InvalidArgumentException('Value must be of type: ' . RouteInterface::class);
        }

        if (empty($value->getStaticPrefix())) {
            return null;
        }

        $value->setContent($entity);
        $value->generateRouteName();

        $translationRoute = new TranslationRoute();
        $translationRoute->setLocale($locale);
        $translationRoute->setProperty($property);
        $translationRoute->setRoute($value);

        $this->entityManager->persist($translationRoute);

        return $translationRoute;
    }


    public function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(TranslationRoute::class);
    }

    protected function updateTranslation($translation, $value)
    {
        if (!$value instanceof RouteInterface) {
            throw new \InvalidArgumentException('Value must be of type: ' . RouteInterface::class);
        }

        if ($translation instanceof TranslationRoute) {
            $translation->setRoute($value);
            return;
        }

        throw new \InvalidArgumentException('Must be of type: ' . TranslationRoute::class);
    }

    protected function getTranslationValue($translation)
    {
        if ($translation instanceof TranslationRoute) {
            return $translation->getRoute();
        }

        throw new \InvalidArgumentException('Must be of type: ' . TranslationRoute::class);
    }

    function findTranslations($entity, ?string $property = null): iterable
    {
        return $this->getRepository()->findTranslationRoutes(
            $this->entityResolver->getName($entity),
            $entity->getId(),
            $property
        );
    }
}
