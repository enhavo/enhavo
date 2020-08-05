<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-15
 * Time: 16:58
 */

namespace Enhavo\Bundle\TranslationBundle\AutoGenerator\Generator;

use Enhavo\Bundle\TranslationBundle\AutoGenerator\AbstractLocaleGenerator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocaleDefaultGenerator extends AbstractLocaleGenerator
{
    public function generate($resource, $property, $locale, $options = [])
    {
        $route = $this->routeTranslator->getTranslation($resource, $property, $locale);
        if($route) {
            $route->addDefaults([
                '_locale' => $locale
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
    }

    public function getType()
    {
        return 'locale_default';
    }
}
