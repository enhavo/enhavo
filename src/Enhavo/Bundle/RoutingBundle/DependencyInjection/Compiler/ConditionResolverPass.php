<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.08.18
 * Time: 15:32
 */

namespace Enhavo\Bundle\RoutingBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\RoutingBundle\Matcher\ConditionUrlMatcher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConditionResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $conditionResolver = $container->getParameter('enhavo_routing.condition_resolver');
        if ($conditionResolver) {
            $definition = $container->getDefinition('cmf_routing.final_matcher');
            $definition->setClass(ConditionUrlMatcher::class);
            $definition->addArgument(new Reference($conditionResolver));

            $container->setAlias('enhavo_routing.condition_resolver', $conditionResolver);
        }
    }
}
