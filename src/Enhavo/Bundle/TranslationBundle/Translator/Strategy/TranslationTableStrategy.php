<?php
/**
 * TranslationTableStrategy.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Strategy;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\Property;
use Enhavo\Bundle\TranslationBundle\Model\TranslationTableData;
use Enhavo\Bundle\TranslationBundle\Translator\TranslationStrategyInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRepository;

class TranslationTableStrategy implements TranslationStrategyInterface
{
    use ContainerAwareTrait;

    /**
     * @var Translation[]
     */
    private $updateRefIds = [];

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var string[]
     */
    private $locales = [];

    /**
     * @var array
     */
    private $translationDataMap = [];

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct($locales, $defaultLocale, EntityManagerInterface $em)
    {
        foreach($locales as $locale => $data) {
            $this->locales[] = $locale;
        }
        $this->defaultLocale = $defaultLocale;
        $this->em = $em;
    }


    public function storeValue($entity, Metadata $metadata, Property $property)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $values = $accessor->getValue($entity, $property->getName());
        if(is_array($values)) {
            $value = $this->storeValues($values, $entity, $property, $metadata);
            $accessor->setValue($entity, $property->getName(), $value);
        }
    }

    public function addTranslationData($entity, $metadata, Property $property, $data)
    {
        $translationData = new TranslationTableData();
        $translationData->setEntity($entity);
        $translationData->setProperty($property);
        $translationData->setValues($data);

        $oid = spl_object_hash($entity);
        if(!isset($this->translationDataMap[$oid])) {
            $this->translationDataMap[$oid] = [];
        }

        $this->translationDataMap[$oid][] = $translationData;
    }

    public function normalizeTranslationData($data)
    {
        $translationData = [];
        foreach($data as $locale => $value) {
            if($locale == $this->defaultLocale) {
                continue;
            }
            $translationData[$locale] = $value;
        }
        return $translationData;
    }

    public function normalizeFormData($data)
    {
        return $data[$this->defaultLocale];
    }

    public function storeTranslationData($entity, $metadata)
    {
        $oid = spl_object_hash($entity);
        if(!isset($this->translationDataMap[$oid])) {
            return;
        }

        $translationData = $this->translationDataMap[$oid];
        $repository = $this->getRepository();
        $unsetKeys = [];

        foreach($translationData as $key => $data) {
            $unsetKeys[] = $key;
            foreach($data->getValues() as $locale => $value) {
                $translation = $repository->findOneBy([
                    'class' => $metadata->getClass(),
                    'refId' => $entity->getId(),
                    'property' => $data->getProperty()->getName(),
                    'locale' => $locale
                ]);

                if($translation === null || $entity->getId() === null) {
                    $translation = new Translation();
                    $translation->setClass($metadata->getClass());
                    $translation->setRefId($entity->getId());
                    $translation->setProperty($data->getProperty()->getName());
                    $translation->setLocale($locale);
                    $this->em->persist($translation);
                    $this->updateRefIds[] = $translation;
                }
                $translation->setTranslation($value);
                $translation->setObject($entity);
            }
        }

        unset($this->translationDataMap[$oid]);

        return;
    }

    public function getTranslationData($entity, $metadata, Property $property)
    {
        // TODO: Implement getTranslationData() method.
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

    public function delete($entity, Metadata $metadata)
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

    public function getTranslations($entity, Metadata $metadata, Property $property)
    {
        $data = [];
        $translations = null;
        if(is_object($entity) && method_exists($entity, 'getId')) {
            /** @var Translation[] $translations */
            $translations = $this->getRepository()->findBy([
                'class' => $metadata->getClass(),
                'refId' => $entity->getId(),
                'property' => $property->getName(),
            ]);
        } else {
            foreach($this->locales as $locale) {
                $data[$locale] = null;
            }
            return $data;
        }

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

    public function translate($entity, Metadata $metadata, Property $property, $locale)
    {
        if($locale === $this->defaultLocale) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        /** @var Translation $translation */
        $translation = $this->getRepository()->findOneBy([
            'class' => $metadata->getClass(),
            'refId' => $entity->getId(),
            'property' => $property->getName(),
            'locale' => $locale
        ]);
        $value = '';
        if($translation !== null && $translation->getTranslation() !== null) {
            $value = $translation->getTranslation();
        }
        $accessor->setValue($entity, $property->getName(), $value);
    }
}