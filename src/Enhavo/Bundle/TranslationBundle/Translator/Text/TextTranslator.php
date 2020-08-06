<?php
/**
 * TextTranslator.php
 *
 * @since 03/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Text;

use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\TranslationBundle\Exception\TranslationException;

/**
 * Class TextTranslator
 *
 * @package Enhavo\Bundle\TranslationBundle
 */
class TextTranslator
{
    use ContainerAwareTrait;

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
     * @var DataMap
     */
    private $buffer;

    /**
     * @var DataMap
     */
    private $originalData;

    /**
     * @var string[]
     */
    private $translatedLocale;

    public function __construct(MetadataRepository $metadataRepository, $defaultLocale, EntityResolverInterface $entityResolver)
    {
        $this->metadataRepository = $metadataRepository;
        $this->defaultLocale = $defaultLocale;
        $this->entityResolver = $entityResolver;

        $this->buffer = new DataMap;
        $this->originalData = new DataMap;
        $this->translatedLocale = [];
    }

    public function setTranslation($entity, $property, $locale, $value): void
    {
        $this->checkEntity($entity);

        if($locale == $this->defaultLocale) {
            return;
        }

        $translation = $this->buffer->load($entity, $property, $locale);
        if($translation instanceof Translation) {
            $translation->setTranslation($value);
            return;
        }

        $translation = $this->load($entity, $property, $locale);
        if($translation === null) {
            $translation = $this->createTranslation($entity, $property, $locale, $value);
        } else {
            $translation->setTranslation($value);
        }

        $this->buffer->store($entity, $property, $locale, $translation);
    }

    public function getTranslation($entity, $property, $locale): ?string
    {
        $this->checkEntity($entity);

        if($locale == $this->defaultLocale) {
            return null;
        }

        $translation = $this->buffer->load($entity, $property, $locale);
        if($translation !== null) {
            return $translation->getTranslation();
        }

        $translation = $this->load($entity, $property, $locale);
        if($translation !== null) {
            $this->buffer->store($entity, $property, $locale, $translation);
            return $translation->getTranslation();
        }

        return null;
    }

    public function isTranslated($entity)
    {
        $oid = spl_object_hash($entity);
        return isset($this->translatedLocale[$oid]);
    }

    public function translate($entity, $locale)
    {
        $this->checkEntity($entity);
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        // translation data is stored inside the object
        if($locale === $this->defaultLocale) {
            return;
        }

        $oid = spl_object_hash($entity);
        if(isset($this->translatedLocale[$oid])) {
            throw new TranslationException('Entity was already translated. It could only translated once. Please detach before using translate again');
        }
        $this->translatedLocale[$oid] = $locale;

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach($metadata->getProperties() as $property) {
            if(!$this->isTypeAccepted($property->getType())) {
                return null;
            }

            $newValue = $this->getTranslation($entity, $property->getProperty(), $locale);
            $oldValue = $accessor->getValue($entity, $property->getProperty());

            $this->originalData->store($entity, $property->getProperty(), null, $oldValue);
            $accessor->setValue($entity, $property->getProperty(), $newValue);
        }
    }

    public function detach($entity)
    {
        $this->checkEntity($entity);
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $oid = spl_object_hash($entity);
        if(!isset($this->translatedLocale[$oid])) {
            throw new TranslationException('Entity was not translated. You can only detach already translated objects');
        }

        $locale = $this->translatedLocale[$oid];

        foreach($metadata->getProperties() as $property) {
            if(!$this->isTypeAccepted($property->getType())) {
                return null;
            }

            $originalValue = $this->originalData->load($entity, $property->getProperty(), null);
            $translationValue = $accessor->getValue($entity, $property->getProperty());
            $this->setTranslation($entity, $property->getProperty(), $locale, $translationValue);
            $accessor->setValue($entity, $property->getProperty(), $originalValue);
        }

        unset($this->translatedLocale[$oid]);
        $this->originalData->delete($entity);
    }

    public function translatable($entity)
    {
        return $this->metadataRepository->hasMetadata($entity);
    }

    private function getAcceptedTypes()
    {
        return ['text'];
    }

    private function getRepository()
    {
        return $this->container->get('doctrine.orm.entity_manager')->getRepository(Translation::class);
    }

    private function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    private function isTypeAccepted($type)
    {
        return in_array($type, $this->getAcceptedTypes());
    }

    private function checkEntity($entity)
    {
        if(!$this->translatable($entity)) {
            throw new TranslationException(sprintf('Entity "%s" is not translatable', get_class($entity)));
        }
    }

    private function createTranslation($entity, $property, $locale, $data): Translation
    {
        $translation = new Translation();
        $translation->setObject($entity);
        $translation->setProperty($property);
        $translation->setLocale($locale);
        $translation->setTranslation($data);
        $this->getEntityManager()->persist($translation);
        return $translation;
    }

    private function load($entity, $property, $locale): ?Translation
    {
        $translation = $this->getRepository()->findOneBy([
            'class' => $this->entityResolver->getName($entity),
            'refId' => $entity->getId(),
            'property' => $property,
            'locale' => $locale
        ]);
        return $translation;
    }

    public function delete($entity)
    {
        $this->checkEntity($entity);

        /** @var Translation[] $translations */
        $translations = $this->getRepository()->findBy([
            'class' => $this->entityResolver->getName($entity),
            'refId' => $entity->getId()
        ]);

        foreach($translations as $translation) {
            $this->getEntityManager()->remove($translation);
        }
    }

    /**
     * @return string
     */
    protected function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    /**
     * @return EntityResolverInterface
     */
    protected function getEntityResolver(): EntityResolverInterface
    {
        return $this->entityResolver;
    }




}
