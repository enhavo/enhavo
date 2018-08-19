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
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteNameGenerator extends AbstractGenerator
{
    public function generate($resource, $options = [])
    {
        /** @var RouteInterface $route */
        $route = $this->getProperty($resource, $options['route_property']);
        if(!$options['overwrite'] && $route->getName()) {
            return;
        }
        $route->setName($this->getRandomName());
    }

    private function getRandomName()
    {
        return uniqid();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'route_property' => 'route',
            'overwrite' => false,
        ]);
    }

    public function getType()
    {
        return 'route_name';
    }
}