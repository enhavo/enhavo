<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        foreach ($data as $locale => $value) {
            if ($locale !== $this->translationManager->getDefaultLocale()) {
                $this->translationManager->setTranslation($this->data, $this->property, $locale, $value);
            }
        }

        return $data[$this->translationManager->getDefaultLocale()];
    }
}
