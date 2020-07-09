<?php


namespace Enhavo\Bundle\TranslationBundle\Form\Transformer;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\Form\DataTransformerInterface;

class TranslationValueTransformer implements DataTransformerInterface
{
    /** @var TranslationManager */
    private $translationManager;

    /** @var string */
    private $property;

    /** @var object|string */
    private $data;

    /**
     * TranslationValueTransformer constructor.
     * @param TranslationManager $translationManager
     * @param object $data
     * @param string $property
     */
    public function __construct(TranslationManager $translationManager, object $data, string $property)
    {
        $this->translationManager = $translationManager;
        $this->property = $property;
        $this->data = $data;
    }

    public function transform($value)
    {
        $data = [$this->translationManager->getDefaultLocale() => $value];

        $translations = $this->translationManager->getTranslations($this->data, $this->property);
        foreach ($translations as $locale => $value) {
            $data[$locale] = $value;
        }

        return $data;
    }

    public function reverseTransform($value)
    {
        $data = $value;
        foreach($data as $locale => $value) {
            if($locale !== $this->translationManager->getDefaultLocale()) {
                $this->translationManager->setTranslation($this->data, $this->property, $locale, $value);
            }
        }
        return $data[$this->translationManager->getDefaultLocale()];
    }
}
