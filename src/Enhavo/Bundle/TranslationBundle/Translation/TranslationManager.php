<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-25
 * Time: 02:14
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Enhavo\Bundle\AppBundle\Exception\TypeMissingException;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
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

    /** @var Translation[] */
    private $translation = [];

    public function __construct(
        MetadataRepository $metadataRepository,
        FactoryInterface $factory,
        $locales,
        $defaultLocale,
        $enabled,
        $translationPaths,
        RequestStack $requestStack
    )
    {
        $this->metadataRepository = $metadataRepository;
        $this->factory = $factory;
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
        $this->enabled = $enabled;
        $this->translationPaths = $translationPaths;
        $this->requestStack = $requestStack;
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

    public function translate($object, $locale)
    {
        $properties = $this->getObjectProperties($object);

        $types = [];
        foreach ($properties as $property) {
            if (!isset($types[$property->getType()])) {
                $type = $this->getTranslation($object, $property->getProperty());
                $type->translate($object, $locale);
                $types[$property->getType()] = $type;
            }
        }
    }

    public function detach($object)
    {
        $properties = $this->getObjectProperties($object);

        $types = [];
        foreach ($properties as $property) {
            if (!isset($types[$property->getType()])) {
                $type = $this->getTranslation($object, $property->getProperty());
                $type->detach($object);
                $types[$property->getType()] = $type;
            }
        }
    }

    public function delete($object)
    {
        $properties = $this->getObjectProperties($object);

        $types = [];
        foreach ($properties as $property) {
            if (!isset($types[$property->getType()])) {
                $translation = $this->getTranslation($object, $property->getProperty());
                $translation->delete($object);
                $types[$property->getType()] = $translation;
            }
        }
    }

    /**
     * @param $object
     * @return PropertyNode[]
     */
    private function getObjectProperties($object): array
    {
        $metadata = $this->metadataRepository->getMetadata($object);
        if (null === $metadata) {
            return [];
        }
        return $metadata->getProperties() ?? [];
    }

    private function getTranslation($data, $propertyName)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($data);
        $property = $metadata->getProperty($propertyName);

        if (!isset($this->translation[$property->getType()])) {
            $this->translation[$property->getType()] = $this->factory->create(array_merge(['type' => $property->getType()], $property->getOptions()));
        }

        return $this->translation[$property->getType()];
    }
}
