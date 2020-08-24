<?php
/**
 * TextTranslator.php
 *
 * @since 03/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Media;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationFile;
use Enhavo\Bundle\TranslationBundle\Translator\DataMap;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class FileTranslator
 * @package Enhavo\Bundle\TranslationBundle\Translator\Media
 */
class FileTranslator implements TranslatorInterface
{

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var EntityResolverInterface
     */
    private $entityResolver;

    /**
     * @var DataMap
     */
    private $buffer;

    /**
     * @var DataMap
     */
    private $originalData;


    public function __construct(EntityManagerInterface $entityManager, EntityResolverInterface $entityResolver, $defaultLocale)
    {
        $this->entityManager = $entityManager;
        $this->defaultLocale = $defaultLocale;
        $this->entityResolver = $entityResolver;

        $this->buffer = new DataMap();
        $this->originalData = new DataMap();
    }

    public function setTranslation($entity, $property, $locale, $value): void
    {
        if ($locale == $this->defaultLocale) {
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
        if ($locale == $this->defaultLocale) {
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
        if ($locale === $this->defaultLocale) {
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
        $accessor = PropertyAccess::createPropertyAccessor();

        $originalValue = $this->originalData->load($entity, $property, null);
        $translationValue = $accessor->getValue($entity, $property);
        $this->setTranslation($entity, $property, $locale, $translationValue);
        $accessor->setValue($entity, $property, $originalValue);

        $this->originalData->delete($entity);
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
        $translation = $this->getRepository()->findOneBy([
            'class' => $this->entityResolver->getName($entity),
            'refId' => $entity->getId(),
            'property' => $property,
            'locale' => $locale
        ]);
        return $translation;
    }

    public function delete($entity, string $property)
    {
        /** @var TranslationFile[] $translations */
        $translations = $this->getRepository()->findBy([
            'class' => $this->entityResolver->getName($entity),
            'property' => $property,
            'refId' => $entity->getId(),
        ]);

        foreach ($translations as $translation) {
            $this->entityManager->remove($translation);
        }
    }

    private function getRepository()
    {
        return $this->entityManager->getRepository(TranslationFile::class);
    }
}
