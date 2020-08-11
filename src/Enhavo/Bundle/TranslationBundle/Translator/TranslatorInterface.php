<?php


namespace Enhavo\Bundle\TranslationBundle\Translator;

interface TranslatorInterface
{
    public function setTranslation($entity, $property, $locale, $value): void;

    public function getTranslation($entity, $property, $locale);

    public function translate($entity, $locale);

    public function detach($entity);

    public function getAcceptedTypes(): array;

}
