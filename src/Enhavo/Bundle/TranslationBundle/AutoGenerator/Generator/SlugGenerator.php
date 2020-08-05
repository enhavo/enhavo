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

class SlugGenerator extends AbstractLocaleGenerator
{
    public function generate($resource, $property, $locale, $options = [])
    {
        $value = $this->textTranslator->getTranslation($resource, $options['property'], $locale);
        if($value !== null) {
            $slug = $this->textTranslator->getTranslation($resource, $property, $locale);
            if(!$options['overwrite'] && $slug) {
                return;
            }
            $this->textTranslator->setTranslation($resource, $property, $locale, Slugifier::slugify($value));
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
        return 'slug';
    }
}
