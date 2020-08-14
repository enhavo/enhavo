<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-25
 * Time: 02:14
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\TranslationBundle\Exception\TranslationException;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\PropertyNode;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class TranslationManager
{
    /** @var MetadataRepository */
    private $metadataRepository;

    /** @var FactoryInterface */
    private $factory;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var LocaleResolverInterface */
    protected $localeResolver;

    /** @var string[] */
    private $locales;

    /** @var string */
    private $defaultLocale;

    /** @var boolean */
    private $enabled;

    /** @var RequestStack */
    private $translationPaths;

    /** @var RequestStack */
    private $requestStack;

    /** @var boolean|null */
    private $cachedTranslation = null;

    /** @var Translation[][] */
    private $translation = [];

    /** @var string[] */
    private $translatedLocale;

    public function __construct(
        MetadataRepository $metadataRepository,
        FactoryInterface $factory,
        EntityManagerInterface $entityManager,
        LocaleResolverInterface $localeResolver,
        $locales,
        $defaultLocale,
        $enabled,
        $translationPaths,
        RequestStack $requestStack
    )
    {
        $this->metadataRepository = $metadataRepository;
        $this->factory = $factory;
        $this->entityManager = $entityManager;
        $this->localeResolver = $localeResolver;
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
        $this->enabled = $enabled;
        $this->translationPaths = $translationPaths;
        $this->requestStack = $requestStack;
        $this->translatedLocale = [];
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function isTranslatable(?object $data, $property = null): bool
    {
        if ($data === null) {
            return false;
        }

        if (!$this->metadataRepository->hasMetadata($data)) {
            return false;
        }

        if ($property === null) {
            return true;
        }

        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($data);

        return $metadata->getProperty($property) !== null;
    }

    public function getLocales()
    {
        return $this->locales;
    }

    public function isTranslation()
    {
        if ($this->cachedTranslation !== null) {
            return $this->cachedTranslation;
        }

        $this->cachedTranslation = false;
        $request = $this->requestStack->getMasterRequest();
        $path = $request->getPathInfo();
        foreach ($this->translationPaths as $regex) {
            if (preg_match($regex, $path)) {
                $this->cachedTranslation = true;
                break;
            }
        }
        return $this->cachedTranslation;
    }

    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    public function getValidationConstraints($data, $property, $locale)
    {
        return $this->getTranslation($data, $property)->getValidationConstraints($data, $property, $locale);
    }

    public function getTranslations($data, $property)
    {
        $translationValues = [];
        $translation = $this->getTranslation($data, $property);
        foreach ($this->locales as $locale) {
            if ($locale == $this->getDefaultLocale()) {
                continue;
            }
            $translationValues[$locale] = $translation->getTranslation($data, $property, $locale);
        }
        return $translationValues;
    }

    public function setTranslation($data, $property, $locale, $value)
    {
        $this->getTranslation($data, $property)->setTranslation($data, $property, $locale, $value);
    }

    /**
     * @param $object
     * @param $locale
     * @throws TranslationException
     */
    public function translate($object, $locale)
    {
        $this->checkEntity($object);

        if ($this->isTranslated($object)) {
            throw new TranslationException('Entity was already translated. It can only be translated once. Please detach before using translate again');
        }

        $properties = $this->getObjectProperties($object);

        foreach ($properties as $property) {
            $type = $this->getTranslation($object, $property->getProperty());
            $type->translate($object, $property->getProperty(), $locale);
        }

        $this->setTranslated($object, $locale);
    }

    public function detach($object)
    {
        $this->checkEntity($object);

        if (!$this->isTranslated($object)) {
            throw new TranslationException('Entity was not translated. You can only detach already translated objects');
        }

        $locale = $this->getTranslatedLocale($object);
        $properties = $this->getObjectProperties($object);

        foreach ($properties as $property) {
            $type = $this->getTranslation($object, $property->getProperty());
            $type->detach($object, $property->getProperty(), $locale);
        }

        $this->setTranslated($object, false);
    }

    public function delete($object)
    {
        $this->checkEntity($object);

        $properties = $this->getObjectProperties($object);

        $types = [];
        foreach ($properties as $property) {
            if (!isset($types[$property->getType()])) {
                $translation = $this->getTranslation($object, $property->getProperty());
                // todo: isTypeAccepted?
                $translation->delete($object);
                $types[$property->getType()] = $translation;
            }
        }
    }

    private function isTranslated($entity): bool
    {
        $oid = spl_object_hash($entity);
        return isset($this->translatedLocale[$oid]);
    }

    /**
     * @param $object
     * @return string|null
     */
    private function getTranslatedLocale($object): ?string
    {
        $oid = spl_object_hash($object);
        return $this->translatedLocale[$oid];
    }

    private function setTranslated($entity, $value)
    {
        $oid = spl_object_hash($entity);
        if (false === $value) {
            unset($this->translatedLocale[$oid]);

        } else {
            $this->translatedLocale[$oid] = $value;
        }
    }

    /**
     * @param $entity
     * @throws TranslationException
     */
    private function checkEntity($entity)
    {
        if (!$this->metadataRepository->hasMetadata($entity)) {
            throw new TranslationException(sprintf('Entity "%s" is not translatable', get_class($entity)));
        }
    }

    /**
     * @param $object
     * @return PropertyNode[]
     */
    private function getObjectProperties($object): array
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($object);

        return $metadata->getProperties() ?? [];
    }

    private function getTranslation($data, $propertyName)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($data);
        $property = $metadata->getProperty($propertyName);
        $className = $metadata->getClassName();

        if (!isset($this->translation[$className])) {
            $this->translation[$className] = [];
        }
        if (!isset($this->translation[$className][$propertyName])) {
            $this->translation[$className][$propertyName] = $this->factory->create(array_merge(['type' => $property->getType()], $property->getOptions()));
        }

        return $this->translation[$className][$propertyName];
    }


    public function fetchBySlug($class, $slug, $locale = null, $allowFallback = false)
    {
        if ($locale === null) {
            $locale = $this->localeResolver->resolve();
        }

        if ($locale === $this->getDefaultLocale()) {
            $entity = $this->entityManager->getRepository($class)->findOneBy([
                'slug' => $slug
            ]);
            return $entity;
        }

        /** @var Translation $translation */
        $translation = $this->entityManager->getRepository(Translation::class)->findOneBy([
            'class' => $this->getEntityResolver()->getName($class),
            'property' => 'slug',
            'translation' => $slug,
            'locale' => $locale
        ]);

        if ($translation !== null) {
            return $translation->getObject();
        }

        if ($allowFallback) {
            return $this->fetch($class, $slug, $this->getDefaultLocale(), false);
        }

        return null;
    }
}
