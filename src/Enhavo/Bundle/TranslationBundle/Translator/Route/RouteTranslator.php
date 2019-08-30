<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-30
 * Time: 00:46
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Route;


class RouteTranslator
{
    public function setTranslation($entity, $property, $locale, $value): void
    {
        $this->checkEntity($entity);

        if($locale == $this->defaultLocale) {
            return;
        }

        $translation = $this->load($entity, $property, $locale);
        if($translation === null) {
            $translation = $this->createTranslation($entity, $property, $locale, $value);
        } else {
            $translation->setTranslation($value);
        }
    }

    public function getTranslation($entity, $property, $locale): ?string
    {
        $this->checkEntity($entity);

        if($locale == $this->defaultLocale) {
            return null;
        }

        $translation = $this->buffer->load($entity, $property, $locale);
        if($translation !== null) {
            return $translation->getTranslation();
        }

        $translation = $this->load($entity, $property, $locale);
        if($translation !== null) {
            $this->buffer->store($entity, $property, $locale, $translation);
            return $translation->getTranslation();
        }

        return null;
    }

    private function checkEntity($entity)
    {
        if(!$this->translatable($entity)) {
            throw new TranslationException(sprintf('Entity "%s" is not translatable', get_class($entity)));
        }
    }
}
