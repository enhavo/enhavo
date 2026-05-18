<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\FrameworkBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\FrameworkBundle\Util\NameTransformer;
use Enhavo\Bundle\TranslationBundle\Entity\Translation as TranslationEntity;
use Enhavo\Bundle\TranslationBundle\Exception\TranslationException;
use Enhavo\Bundle\TranslationBundle\Locale\LocaleProviderInterface;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\PropertyNode;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Component\Type\FactoryInterface;

class TranslationManager
{
    private array $translation = [];

    /** @var string[] */
    private array $translatedLocale;

    private NameTransformer $nameTransformer;

    public function __construct(
        private readonly MetadataRepository $metadataRepository,
        private readonly FactoryInterface $factory,
        private readonly EntityManagerInterface $entityManager,
        private readonly LocaleResolverInterface $localeResolver,
        private readonly EntityResolverInterface $entityResolver,
        private readonly LocaleProviderInterface $localeProvider,
        private $enabled,
    ) {
        $this->translatedLocale = [];
        $this->nameTransformer = new NameTransformer();
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function isTranslatable(?object $data, $property = null): bool
    {
        if (null === $data) {
            return false;
        }

        if (!$this->metadataRepository->hasMetadata($data)) {
            return false;
        }

        if (null === $property) {
            return true;
        }

        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($data);

        return null !== $metadata->getProperty($property);
    }

    public function isFormTranslatable(object $data, string $property)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($data);
        if ($metadata === null) {
            return false;
        }

        $propertyNode = $metadata->getProperty($property);
        if ($propertyNode === null) {
            return false;
        }

        /** @var Translation $translation */
        $translation = $this->factory->create(array_merge([
            'type' => $propertyNode->getType(),
        ], $propertyNode->getOptions()));

        return $translation->isFormTranslatable($data, $property);
    }

    public function getLocales()
    {
        return $this->localeProvider->getLocales();
    }

    public function getDefaultLocale()
    {
        return $this->localeProvider->getDefaultLocale();
    }

    public function getTranslations($data, $property)
    {
        $property = $this->nameTransformer->camelCase($property, true);

        $translationValues = [];
        $translation = $this->getTranslation($data, $property);
        foreach ($this->localeProvider->getLocales() as $locale) {
            if ($locale == $this->getDefaultLocale()) {
                continue;
            }
            $translationValues[$locale] = $translation->getTranslation($data, $property, $locale);
        }

        return $translationValues;
    }

    public function getProperty($resource, $property, $locale)
    {
        $property = $this->nameTransformer->camelCase($property, true);

        $translation = $this->getTranslation($resource, $property);
        if ($locale === $this->getDefaultLocale()) {
            return $translation->getDefaultValue($resource, $property);
        }

        return $translation->getTranslation($resource, $property, $locale);
    }

    public function setTranslation($data, $property, $locale, $value)
    {
        $property = $this->nameTransformer->camelCase($property, true);

        $this->getTranslation($data, $property)->setTranslation($data, $property, $locale, $value);
    }

    /**
     * @throws TranslationException
     */
    public function translate($object, $locale)
    {
        $this->checkEntity($object);

        if ($this->isTranslated($object)) {
            throw new TranslationException('Entity was already translated. It can only be translated once. Please detach before using translate again');
        }

        $properties = $this->getObjectProperties($object);

        foreach ($properties as $property) {
            $type = $this->getTranslation($object, $property->getProperty());
            $type->translate($object, $property->getProperty(), $locale);
        }

        $this->setTranslated($object, $locale);
    }

    public function detach($object)
    {
        $this->checkEntity($object);

        if (!$this->isTranslated($object)) {
            return;
        }

        $locale = $this->getTranslatedLocale($object);
        $properties = $this->getObjectProperties($object);

        foreach ($properties as $property) {
            $type = $this->getTranslation($object, $property->getProperty());
            $type->detach($object, $property->getProperty(), $locale);
        }

        $this->setTranslated($object, false);
    }

    public function delete($object)
    {
        $this->checkEntity($object);

        $properties = $this->getObjectProperties($object);

        foreach ($properties as $property) {
            $translation = $this->getTranslation($object, $property->getProperty());
            $translation->delete($object, $property->getProperty());
        }
    }

    private function isTranslated($entity): bool
    {
        $oid = spl_object_hash($entity);

        return isset($this->translatedLocale[$oid]);
    }

    private function getTranslatedLocale($object): ?string
    {
        $oid = spl_object_hash($object);

        return $this->translatedLocale[$oid];
    }

    private function setTranslated($entity, $value)
    {
        $oid = spl_object_hash($entity);
        if (false === $value) {
            unset($this->translatedLocale[$oid]);
        } else {
            $this->translatedLocale[$oid] = $value;
        }
    }

    /**
     * @throws TranslationException
     */
    private function checkEntity($entity)
    {
        if (!$this->metadataRepository->hasMetadata($entity)) {
            throw new TranslationException(sprintf('Entity "%s" is not translatable', get_class($entity)));
        }
    }

    /**
     * @return PropertyNode[]
     */
    private function getObjectProperties($object): array
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($object);

        return $metadata->getProperties() ?? [];
    }

    /**
     * @return Translation
     */
    private function getTranslation($data, $propertyName)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($data);
        $property = $metadata->getProperty($propertyName);
        $className = $metadata->getClassName();

        if (!isset($this->translation[$className])) {
            $this->translation[$className] = [];
        }
        if (!isset($this->translation[$className][$propertyName])) {
            $this->translation[$className][$propertyName] = $this->factory->create(array_merge(['type' => $property->getType()], $property->getOptions()));
        }

        return $this->translation[$className][$propertyName];
    }

    public function fetchBySlug($class, $slug, $locale = null, $allowFallback = false)
    {
        if (null === $locale) {
            $locale = $this->localeResolver->resolve();
        }

        if ($locale === $this->getDefaultLocale()) {
            $entity = $this->entityManager->getRepository($class)->findOneBy([
                'slug' => $slug,
            ]);

            return $entity;
        }

        /** @var TranslationEntity $translation */
        $translation = $this->entityManager->getRepository(TranslationEntity::class)->findOneBy([
            'class' => $this->entityResolver->getName($class),
            'property' => 'slug',
            'translation' => $slug,
            'locale' => $locale,
        ]);

        if (null !== $translation) {
            return $translation->getObject();
        }

        if ($allowFallback) {
            return $this->fetchBySlug($class, $slug, $this->getDefaultLocale(), false);
        }

        return null;
    }

    public function applyAutoTranslation($data, $locale, ?string $property = null): void
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($data);
        $properties = $metadata->getProperties();
        foreach ($properties as $propertyName => $propertyNode) {
            if ($property === null || $propertyName === $property) {
                /** @var Translation $translation */
                $translation = $this->factory->create(array_merge([
                    'type' => $propertyNode->getType(),
                ], $propertyNode->getOptions()));
                $translation->autoTranslate($data, $propertyName, $locale);
            }
        }
    }
}
