<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        if ($container->hasDefinition('cmf_routing.final_matcher')) {
            $definition = $container->getDefinition('cmf_routing.final_matcher');
            $definition->setClass(ConditionUrlMatcher::class);
            $definition->addArgument(new Reference(ResolverInterface::class));
        }
    }
}
