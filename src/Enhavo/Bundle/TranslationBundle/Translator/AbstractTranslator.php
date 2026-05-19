<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\TranslationBundle\Locale\LocaleProviderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractTranslator implements TranslatorInterface
{
    protected DataMap $buffer;
    protected DataMap $originalData;

    /** @var array<string, array> */
    protected array $translationCache = [];

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected EntityResolverInterface $entityResolver,
        protected LocaleProviderInterface $localeProvider
    )
    {
        $this->buffer = new DataMap();
        $this->originalData = new DataMap();
    }

    public function setTranslation($entity, $property, $locale, $value): void
    {
        if ($locale == $this->localeProvider->getDefaultLocale()) {
            return;
        }

        $this->loadBuffer($entity);

        $translation = $this->buffer->load($entity, $property, $locale);

        if ($translation === null) {
            $translation = $this->createTranslation($entity, $property, $locale, $value);
            if ($translation) {
                $this->buffer->store($entity, $property, $locale, $translation);
            }
        } else {
            $this->updateTranslation($translation, $value);
        }
    }

    public function getTranslation($entity, $property, $locale): mixed
    {
        if ($locale == $this->localeProvider->getDefaultLocale()) {
            return null;
        }

        $this->loadBuffer($entity);

        $translation = $this->buffer->load($entity, $property, $locale);

        if ($translation === null) {
            return null;
        }

        return $this->getTranslationValue($translation);
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

    public function getDefaultValue($entity, string $property)
    {
        $originalValue = $this->originalData->load($entity, $property, null);

        if (null === $originalValue) {
            $accessor = PropertyAccess::createPropertyAccessor();

            return $accessor->getValue($entity, $property);
        }

        return $originalValue;
    }

    public function delete($entity, string $property): void
    {
        $translations = $this->findTranslations($entity, $property);

        foreach ($translations as $translation) {
            $this->entityManager->remove($translation);
        }
    }

    private function loadBuffer($entity): void
    {
        if ($this->buffer->exists($entity)) {
            return;
        }

        $translations = $this->findTranslations($entity);

        foreach ($translations as $translation) {
            $this->buffer->store($entity, $translation->getProperty(), $translation->getLocale(), $translation);
        }
    }

    abstract public function getRepository(): EntityRepository;

    abstract protected function createTranslation($entity, $property, $locale, $value): ?object;

    abstract protected function updateTranslation($translation, $value);

    abstract protected function getTranslationValue($translation);

    abstract protected function findTranslations($entity, ?string $property = null): iterable;
}
