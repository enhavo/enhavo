<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Media;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationFile;
use Enhavo\Bundle\TranslationBundle\Translator\AbstractTranslator;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class FileTranslator
 */
class FileTranslator extends AbstractTranslator
{
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

        // never set null values
        if (null !== $newValue) {
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
        $translation = new TranslationFile();
        $translation->setObject($entity);
        $translation->setProperty($property);
        $translation->setLocale($locale);
        $translation->setFile($value);
        $this->entityManager->persist($translation);

        return $translation;
    }

    protected function updateTranslation($translation, $value): void
    {
        if ($translation instanceof TranslationFile) {
            $translation->setFile($value);
            return;
        }

        throw new \InvalidArgumentException('Must be of type: ' . TranslationFile::class);
    }

    protected function getTranslationValue($translation)
    {
        if ($translation instanceof TranslationFile) {
            return $translation->getFile();
        }

        throw new \InvalidArgumentException('Must be of type: ' . TranslationFile::class);
    }

    public function findTranslations($entity, ?string $property = null): array
    {
        $parameters = [
            'class' => $this->entityResolver->getName($entity),
            'refId' => $entity->getId(),
        ];

        if ($property !== null) {
            $parameters['property'] = $property;
        }

        return $this->getRepository()->findBy($parameters);
    }

    public function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(TranslationFile::class);
    }
}
