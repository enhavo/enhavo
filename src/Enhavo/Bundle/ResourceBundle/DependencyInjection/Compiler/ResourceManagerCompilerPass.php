<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ResourceManagerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('enhavo_resources.resources');

        $resourceManager = $container->findDefinition(ResourceManager::class);

        $services = [];
        foreach ($resources as $key => $config) {
            $name = sprintf('%s.repository', $key);
            $services[$name] = new Reference($name);

            $name = sprintf('%s.factory', $key);
            $services[$name] = new Reference($name);
        }

        $resourceManager->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
