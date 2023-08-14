<?php

namespace Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\MultiTenancyBundle\Matcher\ConditionUrlMatcher;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConditionUrlMatcherCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('cmf_routing.final_matcher');
        $definition->setClass(ConditionUrlMatcher::class);
        $definition->addArgument(new Reference(ResolverInterface::class));
    }
}
