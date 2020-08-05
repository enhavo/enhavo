<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-15
 * Time: 16:58
 */

namespace Enhavo\Bundle\TranslationBundle\AutoGenerator\Generator;

use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\TranslationBundle\AutoGenerator\AbstractLocaleGenerator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalePrefixGenerator extends AbstractLocaleGenerator
{
    public function generate($resource, $property, $locale, $options = [])
    {
        $value = $this->textTranslator->getTranslation($resource, $options['property'], $locale);
        if($value !== null) {
            $route = $this->routeTranslator->getTranslation($resource, $property, $locale);
            if(!$options['overwrite'] && $route->getStaticPrefix()) {
                return;
            }
            $route->setStaticPrefix(sprintf('/%s/%s', $locale, Slugifier::slugify($value)));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'overwrite' => false
        ]);
        $resolver->setRequired([
            'property'
        ]);
    }

    public function getType()
    {
        return 'locale_prefix';
    }
}
