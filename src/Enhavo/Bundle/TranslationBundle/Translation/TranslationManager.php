<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-25
 * Time: 02:14
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TranslationManager
{
    public function isTranslatable($data, $property = null)
    {
        if($data instanceof Article && $property == null) {
            return true;
        }

        if($data instanceof Article && $property == 'title') {
            return true;
        }

        return false;
    }

    public function getFormType($data, $property)
    {
        if($data instanceof Article && $property == 'title') {
            return TextType::class;
        }

        return null;
    }

    public function getLocales()
    {
        return ['de', 'en', 'fr'];
    }

    public function isTranslation()
    {
        return true;
    }

    public function getDefaultLocale()
    {
        return 'de';
    }

    public function getTranslations($data, $property)
    {
        return [
            'fr' => 'fr title',
            'en' => 'en title'
        ];
    }

    public function setTranslation($data, $property, $locale, $value)
    {

    }
}
