<?php


namespace Enhavo\Bundle\TranslationBundle\Translator;

interface TranslatorInterface
{
    public function setTranslation($entity, $property, $locale, $value): void;

    public function getTranslation($entity, $property, $locale);

    public function translate($entity, string $property, string $locale, array $options);

    public function detach($entity, string $property, string $locale, array $options);

    public function delete($entity, string $property);
}
