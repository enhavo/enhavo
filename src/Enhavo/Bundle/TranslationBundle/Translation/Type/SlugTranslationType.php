<?php
/**
 * SlugTranslationStrategy.php
 *
 * @since 20/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Type;

use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\Property;
use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\TranslationBundle\Route\RouteGuesser;
use Enhavo\Bundle\TranslationBundle\Translator\LocaleResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SlugTranslationType extends TranslationTableStrategy
{
    /**
     * @var RouteGuesser
     */
    protected $routeGuesser;

    /**
     * @var array
     */
    protected $updateSlugMap = [];

    public function __construct($locales, LocaleResolver $localeResolver, EntityManagerInterface $em, $routeGuesser)
    {
        parent::__construct($locales, $localeResolver, $em);
        $this->routeGuesser = $routeGuesser;
    }

    public function normalizeToTranslationData($entity, Property $property, $formData, Metadata $metadata)
    {
        if($formData === null) {
            return null;
        }

        $translationData = parent::normalizeToTranslationData($entity, $property, $formData, $metadata);

        /**
         * Because we don't know if the translation data is stored in its strategy or in the form, we need to wait
         * and update the slugs after flush.
         */
        $oid = spl_object_hash($entity);
        if(!isset($this->updateSlugMap[$oid])) {
            $this->updateSlugMap[$oid] = [];
        }
        $this->updateSlugMap[$oid][] = [
            'property' => $property,
            'entity' => $entity,
            'metadata' => $metadata
        ];

        return $translationData;
    }

    public function normalizeToModelData($entity, Property $property, $formData, Metadata $metadata)
    {
        if($formData === null) {
            return null;
        }

        $modelData = parent::normalizeToModelData($entity, $property, $formData, $metadata);
        if(empty($modelData)) {
            $accessor = PropertyAccess::createPropertyAccessor();

            $fields = $property->getOption('fields');
            if(is_string($fields)) {
                $fields = [$fields];
            }

            foreach($fields as $field) {
                $context[] = $accessor->getValue($entity, $field);
            }

            $context = implode(' ', $context);
            $modelData = Slugifier::slugify($context);
            //ToDo: unify slug
        }

        //return null if string is empty, to let doctrine sluggable change this field if possible
        if($modelData == '') {
            return null;
        }
        return $modelData;
    }

    public function postFlush()
    {
        parent::postFlush();

        foreach($this->updateSlugMap as $entities) {
            foreach($entities as $updateSlug) {
                $property = $updateSlug['property'];
                $entity = $updateSlug['entity'];
                $metadata = $updateSlug['metadata'];

                /** @var Translation[] $translations */
                $translations = $this->getRepository()->findBy([
                    'class' => $metadata->getClass(),
                    'refId' => $entity->getId(),
                    'property' => $property->getName(),
                ]);

                foreach($translations as $translation) {
                    $value = $translation->getTranslation();
                    if(empty($value)) {
                        $slug = $this->createSlug($entity, $property, $translation->getLocale(), $metadata);
                        $translation->setTranslation($slug);
                    }
                }
            }
        }

        $this->em->flush();
        $this->updateSlugMap = [];
    }

    protected function createSlug($entity, Property $property, $locale, $metadata)
    {
        $fields = $property->getOption('fields');
        if(is_string($fields)) {
            $fields = [$fields];
        }

        $context = [];
        /** @var Translation $translation */
        foreach($fields as $field) {
            $translation = $this->getRepository()->findOneBy([
                'class' => $metadata->getClass(),
                'refId' => $entity->getId(),
                'property' => $field,
                'locale' => $locale
            ]);
            $context[] = $translation->getTranslation();
        }

        $context = implode(' ', $context);
        $slug = Slugifier::slugify($context);
        //ToDo: unify slug
        return $slug;
    }
}
