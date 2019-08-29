<?php
/**
 * TextTranslator.php
 *
 * @since 03/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Text;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Reference\TargetClassResolverInterface;
use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRepository;
use Enhavo\Bundle\TranslationBundle\Exception\TranslationException;
use Enhavo\Bundle\AppBundle\Metadata\MetadataRepository;
use Enhavo\Bundle\TranslationBundle\Translator\DataMap;


/**
 * Class TextTranslator
 *
 * @package Enhavo\Bundle\TranslationBundle
 */
class TextTranslator
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
     * @var TargetClassResolverInterface
     */
    private $classResolver;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var DataMap
     */
    private $buffer;

    /**
     * @var DataMap
     */
    private $originalData;

    public function __construct(MetadataRepository $metadataRepository, $defaultLocale, TargetClassResolverInterface $classResolver, EntityManagerInterface $em)
    {
        $this->metadataRepository = $metadataRepository;
        $this->defaultLocale = $defaultLocale;
        $this->classResolver = $classResolver;
        $this->em = $em;
        $this->buffer = new DataMap;
        $this->originalData = new DataMap;
    }

    public function setTranslation($entity, $property, $locale, $value)
    {
        $this->checkEntity($entity);
        $translation = $this->createTranslation($entity, $property, $locale, $value);
        $this->buffer->store($entity, $property, $locale, $translation);
    }

    public function getTranslation($entity, $property, $locale)
    {
        $this->checkEntity($entity);
        $translation = $this->buffer->load($entity, $property, $locale);
        if($translation !== null) {
            return $translation;
        }

        $translation = $this->load($entity, $property, $locale);
        if($translation !== null) {
            return $translation;
        }

        return null;
    }

    public function translate($entity, $locale)
    {
        $this->checkEntity($entity);
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        //translation data is stored inside the object
        if($locale === $this->defaultLocale) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        foreach($metadata->getProperties() as $property) {
            if(!$this->isTypeAccepted($property->getType())) {
                return null;
            }

            $newValue = $this->getTranslation($entity, $property->getProperty(), $locale);
            $oldValue = $accessor->getValue($entity, $property->getProperty());

            $this->originalData->store($entity, $property->getProperty(), $locale, $oldValue);
            $accessor->setValue($entity, $property->getProperty(), $newValue);
        }
    }

    public function detach($entity)
    {

    }

    public function translatable($entity)
    {
        return $this->metadataRepository->hasMetadata($entity);
    }

    private function getAcceptedTypes()
    {
        return ['text'];
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
        return $translation;
    }

    private function load($entity, $property, $locale): ?Translation
    {
        return $this->repository->findOneBy([
            'class' => $this->classResolver->resolveClass($entity),
            'refId' => $entity->getId(),
            'property' => $property
        ]);
    }

    public function deleteTranslationData($entity, Metadata $metadata)
    {
        /** @var Translation[] $translations */
        $translations = $this->getRepository()->findBy([
            'class' => $metadata->getClass(),
            'refId' => $entity->getId()
        ]);
        foreach($translations as $translation) {
            $this->getEntityManager()->remove($translation);
        }
    }
}
