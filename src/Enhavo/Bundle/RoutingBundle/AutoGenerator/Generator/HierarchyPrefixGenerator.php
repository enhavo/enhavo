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

class HierarchyPrefixGenerator extends AbstractGenerator
{
    public function generate($resource, $options = [])
    {
        $route = $this->getProperty($resource, $options['route_property']);
        if(!$options['overwrite'] && $route->getStaticPrefix()) {
            return;
        }

        $parents = [];
        $parent = $this->getProperty($resource, $options['parent_property']);
        if($parent) {
            while($parent !== null) {
                $parents[] = $parent;
                $parent = $this->getProperty($parent, $options['parent_property']);
            }
        }

        $slugs = [];
        foreach(array_reverse($parents) as $parent) {
            $slugs[] = Slugifier::slugify($this->getProperty($parent, $options['prefix_property']));
        }
        $slugs[] = Slugifier::slugify($this->getProperty($resource, $options['prefix_property']));
        $route->setStaticPrefix('/'.implode('/', $slugs));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'route_property' => 'route',
            'overwrite' => false,
            'parent_property' => 'parent'
        ]);
        $resolver->setRequired([
            'prefix_property',
        ]);
    }

    public function getType()
    {
        return 'hierarchy_prefix';
    }
}