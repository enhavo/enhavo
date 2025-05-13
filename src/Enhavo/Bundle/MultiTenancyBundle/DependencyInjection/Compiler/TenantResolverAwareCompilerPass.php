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

use Enhavo\Bundle\MultiTenancyBundle\Resolver\TenantResolverAwareInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TenantResolverAwareCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('enhavo_resources.resources');
        foreach ($resources as $resourceName => $resourceConfig) {
            $this->injectIfImplements($resourceConfig, $resourceName, 'controller', $container);
            $this->injectIfImplements($resourceConfig, $resourceName, 'factory', $container);
            $this->injectIfImplements($resourceConfig, $resourceName, 'repository', $container);
        }
    }

    private function injectIfImplements($resourceConfig, $resourceName, $type, ContainerBuilder $container)
    {
        if (!isset($resourceConfig['classes'][$type])) {
            return;
        }
        $class = $resourceConfig['classes'][$type];
        $implements = class_implements($class);
        foreach ($implements as $interface) {
            if (TenantResolverAwareInterface::class === $interface) {
                [$applicationName, $name] = explode('.', $resourceName);
                if ($container->hasDefinition($applicationName.'.'.$type.'.'.$name)) {
                    $definition = $container->getDefinition($applicationName.'.'.$type.'.'.$name);
                    $definition->addMethodCall('setTenantResolver', [
                        new Reference('enhavo_multi_tenancy.resolver'),
                    ]);
                    break;
                }
            }
        }
    }
}
