<?php
/**
 * Translator.php
 *
 * @since 03/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;

use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\MetadataCollection;
use Enhavo\Bundle\TranslationBundle\Metadata\Property;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRepository;

class Translator
{
    use ContainerAwareTrait;

    /**
     * @var MetadataCollection
     */
    private $metadataCollection;

    /**
     * @var Translation[]
     */
    private $updateRefIds = [];

    /**
     * @var string
     */
    private $defaultLocale = 'de';

    /**
     * @var string[]
     */
    private $locales;

    public function __construct(MetadataCollection $metadataCollection)
    {
        $this->metadataCollection = $metadataCollection;
        $this->locales = ['de', 'en', 'us'];
    }

    public function store($entity)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);

        if($metadata !== null) {
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach($metadata->getProperties() as $property) {
                $values = $accessor->getValue($entity, $property->getName());
                if(is_array($values)) {
                    $value = $this->storeValues($values, $entity, $property, $metadata);
                    $accessor->setValue($entity, $property->getName(), $value);
                }
            }
        }
    }

    protected function getEntityManager()
    {
        return $this->container->get('doctrine.orm.default_entity_manager');
    }

    protected function storeValues(array $values, $entity, Property $property, Metadata $metadata)
    {
        $return = null;

        $repository = $this->getRepository();
        foreach($values as $locale => $value) {
            if($this->defaultLocale === $locale) {
                $return = $value;
            } else {
                $translation = $repository->findOneBy([
                    'class' => $metadata->getClass(),
                    'refId' => $entity->getId(),
                    'property' => $property->getName(),
                    'locale' => $locale
                ]);
                if($translation === null || $entity->getId() === null) {
                    $translation = new Translation();
                    $translation->setClass($metadata->getClass());
                    $translation->setRefId($entity->getId());
                    $translation->setProperty($property->getName());
                    $translation->setLocale($locale);
                    $this->updateRefIds[] = $translation;
                }
                $translation->setTranslation($value);
                $translation->setObject($entity);
            }
        }

        return $return;
    }

    /**
     * @return TranslationRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('EnhavoTranslationBundle:Translation');
    }

    public function delete($entity)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);

        if($metadata !== null) {
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

    public function getTranslations($entity, Property $property)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        /** @var Translation[] $translations */
        $translations = $this->getRepository()->findBy([
            'class' => $metadata->getClass(),
            'refId' => $entity->getId(),
            'property' => $property->getName(),
        ]);

        $data = [];
        foreach($this->locales as $locale) {
            if($locale === $this->defaultLocale) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $value = $accessor->getValue($entity, $property->getName());
                $data[$locale] = $value;
                continue;
            }
            $value = null;
            foreach($translations as $translation) {
                if($translation->getLocale() === $locale) {
                    $value = $translation->getTranslation();
                    break;
                }
            }
            $data[$locale] = $value;
        }

        return $data;
    }

    public function updateReferences()
    {
        foreach($this->updateRefIds as $key => $translation) {
            $translation->setRefId($translation->getObject()->getId());
            $this->getEntityManager()->persist($translation);
            unset($this->updateRefIds[$key]);
        }
        $this->getEntityManager()->flush();
    }
}