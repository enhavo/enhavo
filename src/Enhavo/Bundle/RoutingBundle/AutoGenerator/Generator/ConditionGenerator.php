<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 19:50
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator;


use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConditionGenerator extends AbstractGenerator
{
    public function generate(RouteInterface $route, $options)
    {
        if($options['resolve_property']) {

        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'resolve_property' => null,
            'condition' => 'resolver.resolve() == "%s"'
        ]);
    }

    public function getType()
    {
        return 'condition';
    }
}