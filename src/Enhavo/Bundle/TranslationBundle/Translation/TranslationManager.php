<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-25
 * Time: 02:14
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Enhavo\Bundle\AppBundle\Exception\TypeMissingException;
use Enhavo\Bundle\AppBundle\Metadata\MetadataRepository;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Symfony\Component\HttpFoundation\RequestStack;

class TranslationManager
{
    /**
     * @var MetadataRepository
     */
    private $metadataRepository;

    /**
     * @var TypeCollector
     */
    private $collector;

    /**
     * @var string[]
     */
    private $locales;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var RequestStack
     */
    private $translationPaths;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var boolean|null
     */
    private $cachedTranslation = null;

    /**
     * @var Translation[]
     */
    private $types = [];

    public function __construct(
        MetadataRepository $metadataRepository,
        TypeCollector $collector,
        $locales,
        $defaultLocale,
        $enabled,
        $translationPaths,
        RequestStack $requestStack
    ) {
        $this->metadataRepository = $metadataRepository;
        $this->collector = $collector;
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

    public function isTranslatable($data, $property = null): bool
    {
        if($data === null) {
            return false;
        }

        if(!(is_string($data) || is_object($data))) {
            return false;
        }

        if(!$this->metadataRepository->hasMetadata($data)) {
            return false;
        }

        if($property === null) {
            return true;
        }

        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($data);

        return $metadata->getProperty($property) !== null;
    }

    public function getFormType($data, $property)
    {
        $type = $this->getType($data, $property);
        $formType = $type->getFormType();
        return $formType;
    }

    public function getLocales()
    {
        return $this->locales;
    }

    public function isTranslation()
    {
        if($this->cachedTranslation !== null) {
            return $this->cachedTranslation;
        }

        $this->cachedTranslation = false;
        $request = $this->requestStack->getMasterRequest();
        $path = $request->getBasePath();
        foreach($this->translationPaths as $regex) {
            if(preg_match($regex, $path)) {
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

    public function getTranslations($data, $property)
    {
        $translations = [];
        $type = $this->getType($data, $property);
        foreach($this->locales as $locale) {
            if($locale == $this->getDefaultLocale()) {
                continue;
            }
            $translations[$locale] = $type->getTranslation($locale);
        }
        return $translations;
    }

    public function setTranslation($data, $property, $locale, $value)
    {
        $type = $this->getType($data, $property);
        $type->setTranslation($locale, $value);
    }

    private function getType($data, $property)
    {
        foreach($this->types as $type) {
            if($type->getData() === $data && $type->getProperty() === $property) {
                return $type;
            }
        }

        $type = $this->createType($data, $property);
        $this->types[] = $type;
        return $type;
    }

    private function createType($data, $property)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($data);

        $options = $metadata->getProperty($property)->getOptions();
        $type = $metadata->getProperty($property)->getType();

        if(empty($type)) {
            throw new TypeMissingException(sprintf('No type was given to create "%s"', Translation::class));
        }

        /** @var TranslationTypeInterface $translationType */
        $translationType = $this->collector->getType($type);
        $type = new Translation($translationType, $options, $data, $property);
        return $type;
    }
}
