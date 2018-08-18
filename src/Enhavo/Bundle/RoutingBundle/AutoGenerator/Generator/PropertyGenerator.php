<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 19:50
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator;


use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyGenerator extends AbstractGenerator
{
    public function generate(RouteInterface $route, $options)
    {
        $value = $this->getProperty($route->getContent(), $options['property']);
        if($value !== null) {
            $route->setStaticPrefix(sprintf('/%s', Slugifier::slugify($value)));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([]);
        $resolver->setRequired(['property']);
    }

    public function getType()
    {
        return 'property';
    }
}