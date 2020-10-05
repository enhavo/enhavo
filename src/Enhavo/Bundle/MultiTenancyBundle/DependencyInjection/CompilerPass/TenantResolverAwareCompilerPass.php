<?php

namespace Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\CompilerPass;

use Enhavo\Bundle\MultiTenancyBundle\Resolver\TenantResolverAwareInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TenantResolverAwareCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('sylius.resources');
        foreach($resources as $resourceName => $resourceConfig) {
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
        foreach($implements as $interface) {
            if ($interface === TenantResolverAwareInterface::class) {
                [$applicationName, $name] = explode('.', $resourceName);
                if ($container->hasDefinition($applicationName . '.' . $type . '.' . $name)) {
                    $definition = $container->getDefinition($applicationName . '.' . $type . '.' . $name);
                    $definition->addMethodCall('setTenantResolver', [
                        new Reference('enhavo_multi_tenancy.resolver')
                    ]);
                    break;
                }
            }
        }
    }
}
