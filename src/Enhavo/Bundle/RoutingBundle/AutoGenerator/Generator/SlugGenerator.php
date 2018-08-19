<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 19:50
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator;

use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SlugGenerator extends AbstractGenerator
{
    public function generate($resource, $options = [])
    {
        $value = $this->getProperty($resource, $options['property']);
        if($value !== null) {
            if(!$options['overwrite'] && $this->getProperty($resource, $options['slug_property'])) {
                return;
            }
            $slug = Slugifier::slugify($value);
            $accessor = PropertyAccess::createPropertyAccessor();
            $accessor->setValue($resource, $options['slug_property'], $slug);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'slug_property' => 'slug',
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