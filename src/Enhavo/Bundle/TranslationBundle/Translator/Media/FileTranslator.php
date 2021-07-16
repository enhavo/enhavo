<?php
/**
 * TextTranslator.php
 *
 * @since 03/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Media;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationFile;
use Enhavo\Bundle\TranslationBundle\Translator\AbstractTranslator;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class FileTranslator
 * @package Enhavo\Bundle\TranslationBundle\Translator\Media
 */
class FileTranslator extends AbstractTranslator
{
    public function setTranslation($entity, $property, $locale, $value): void
    {
        if ($locale == $this->localeProvider->getDefaultLocale()) {
            return;
        }

        $translation = $this->buffer->load($entity, $property, $locale);
        if ($translation instanceof TranslationFile) {
            $translation->setFile($value);
            return;
        }

        $translation = $this->load($entity, $property, $locale);
        if ($translation === null) {
            $translation = $this->createTranslationFile($entity, $property, $locale, $value);
        } else {
            $translation->setFile($value);
        }

        $this->buffer->store($entity, $property, $locale, $translation);
    }

    public function getTranslation($entity, $property, $locale): ?FileInterface
    {
        if ($locale == $this->localeProvider->getDefaultLocale()) {
            return null;
        }

        $translation = $this->buffer->load($entity, $property, $locale);
        if ($translation !== null) {
            return $translation->getFile();
        }

        $translation = $this->load($entity, $property, $locale);
        if ($translation !== null) {
            $this->buffer->store($entity, $property, $locale, $translation);
            return $translation->getFile();
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

        // never set null values
        if ($newValue !== null) {
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

    private function createTranslationFile($entity, $property, $locale, $data): TranslationFile
    {
        $translation = new TranslationFile();
        $translation->setObject($entity);
        $translation->setProperty($property);
        $translation->setLocale($locale);
        $translation->setFile($data);
        $this->entityManager->persist($translation);
        return $translation;
    }

    private function load($entity, $property, $locale): ?TranslationFile
    {
        /** @var TranslationFile $translation */
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
        return $this->entityManager->getRepository(TranslationFile::class);
    }
}
