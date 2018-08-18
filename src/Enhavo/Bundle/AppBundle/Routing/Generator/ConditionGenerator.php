<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 19:50
 */

namespace Enhavo\Bundle\AppBundle\Routing\Generator;

use Enhavo\Bundle\AppBundle\Model\RouteInterface;
use Enhavo\Bundle\AppBundle\Routing\AbstractGenerator;
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