<?php
/**
 * TranslationTableStrategy.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translation\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;
use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Model\TranslationTableData;
use Enhavo\Bundle\TranslationBundle\Translator\LocaleResolver;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRepository;

class TextTranslationType extends AbstractTranslationType
{
    use ContainerAwareTrait;

    /**
     * @var Translation[]
     */
    protected $updateRefIds = [];
    
    /**
     * @var string[]
     */
    protected $locales = [];

    /**
     * @var array
     */
    protected $translationDataMap = [];

    /**
     * @var LocaleResolver
     */
    protected $localeResolver;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function getFormType(array $options)
    {
        return $options['form_type'];
    }

    public function setTranslation(array $options, $data, $property, $locale, $value)
    {
        return;
    }

    public function getTranslation(array $options, $data, $property, $locale)
    {
        return 'test';
    }

    public function getType()
    {
        return 'text';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setRequired(['form_type']);
    }

    protected function getEntityManager()
    {
        return $this->container->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @return TranslationRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('EnhavoTranslationBundle:Translation');
    }

    public function addTranslationData($entity, Property $property, $data, Metadata $metadata)
    {
        if($data === null) {
            return;
        }

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

    public function normalizeToTranslationData($entity, Property $property, $formData, Metadata $metadata)
    {
        if($formData === null) {
            return null;
        }

        $translationData = [];
        foreach($formData as $locale => $value) {
            if($locale == $this->localeResolver->getLocale()) {
                continue;
            }
            $translationData[$locale] = $value;
        }
        return $translationData;
    }

    public function normalizeToModelData($entity, Property $property, $formData, Metadata $metadata)
    {
        return $formData[$this->localeResolver->getLocale()];
    }

    public function storeTranslationData($entity, Metadata $metadata)
    {
        $oid = spl_object_hash($entity);
        if(!isset($this->translationDataMap[$oid])) {
            return;
        }

        $translationData = $this->translationDataMap[$oid];
        $repository = $this->getRepository();

        foreach($translationData as $data) {
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

    public function getTranslationData($entity, Property $property, Metadata $metadata)
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
            if($locale === $this->localeResolver->getLocale()) {
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

    public function postFlush()
    {
        foreach($this->updateRefIds as $key => $translation) {
            $translation->setRefId($translation->getObject()->getId());
            $this->getEntityManager()->persist($translation);
            unset($this->updateRefIds[$key]);
        }
        $this->getEntityManager()->flush();
    }

//    public function getTranslation($entity, Property $property, $locale, Metadata $metadata)
//    {
//        /** @var Translation $translation */
//        $translation = $this->getRepository()->findOneBy([
//            'class' => $metadata->getClass(),
//            'refId' => $entity->getId(),
//            'property' => $property->getName(),
//            'locale' => $locale
//        ]);
//        $value = '';
//        if($translation !== null && $translation->getTranslation() !== null) {
//            $value = $translation->getTranslation();
//        }
//        return $value;
//    }
}
