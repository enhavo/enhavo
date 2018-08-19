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

class PrefixGenerator extends AbstractGenerator
{
    public function generate($resource, $options = [])
    {
        $value = $this->getProperty($resource, $options['property']);
        if($value !== null) {
            /** @var RouteInterface $route */
            $route = $this->getProperty($resource, $options['route_property']);
            if(!$options['overwrite'] && $route->getStaticPrefix()) {
                return;
            }
            $route->setStaticPrefix(sprintf('/%s', Slugifier::slugify($value)));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'route_property' => 'route',
            'overwrite' => false
        ]);
        $resolver->setRequired([
            'property'
        ]);
    }

    public function getType()
    {
        return 'prefix';
    }
}