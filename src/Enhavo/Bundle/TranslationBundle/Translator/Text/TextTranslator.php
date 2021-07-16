<?php
/**
 * TextTranslator.php
 *
 * @since 03/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Text;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Translator\AbstractTranslator;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class TextTranslator
 * @package Enhavo\Bundle\TranslationBundle\Translator\Text
 */
class TextTranslator extends AbstractTranslator
{
    public function setTranslation($entity, $property, $locale, $value): void
    {
        if ($locale == $this->localeProvider->getDefaultLocale()) {
            return;
        }

        $translation = $this->buffer->load($entity, $property, $locale);
        if ($translation instanceof Translation) {
            $translation->setTranslation($value);
            return;
        }

        $translation = $this->load($entity, $property, $locale);
        if ($translation === null) {
            $translation = $this->createTranslation($entity, $property, $locale, $value);
        } else {
            $translation->setTranslation($value);
        }

        $this->buffer->store($entity, $property, $locale, $translation);
    }

    public function getTranslation($entity, $property, $locale): ?string
    {
        if ($locale == $this->localeProvider->getDefaultLocale()) {
            return null;
        }

        $translation = $this->buffer->load($entity, $property, $locale);
        if ($translation !== null) {
            return $translation->getTranslation();
        }

        $translation = $this->load($entity, $property, $locale);
        if ($translation !== null) {
            $this->buffer->store($entity, $property, $locale, $translation);
            return $translation->getTranslation();
        }

        return null;
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

        // set null values only if fallback is not allowed
        if ($newValue !== null || !$options['allow_fallback']) {
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

    private function createTranslation($entity, $property, $locale, $data): Translation
    {
        $translation = new Translation();
        $translation->setObject($entity);
        $translation->setProperty($property);
        $translation->setLocale($locale);
        $translation->setTranslation($data);
        $this->entityManager->persist($translation);
        return $translation;
    }

    private function load($entity, $property, $locale): ?Translation
    {
        /** @var Translation $translation */
        $translation = $this->getRepository()->findOneBy([
            'class' => $this->entityResolver->getName($entity),
            'refId' => $entity->getId(),
            'property' => $property,
            'locale' => $locale
        ]);
        return $translation;
    }

    public function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Translation::class);
    }
}
