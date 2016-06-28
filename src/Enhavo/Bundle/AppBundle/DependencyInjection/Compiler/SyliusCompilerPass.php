<?php
/**
 * RequestConfigurationCompilerPass.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SyliusCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteRequestConfigurationFactory($container);
        $this->overwriteController($container);
        $this->overwriteEventDispatcher($container);
    }

    protected function overwriteRequestConfigurationFactory(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.resource_controller.request_configuration_factory');
        $definition->replaceArgument(1, 'Enhavo\Bundle\AppBundle\Controller\RequestConfiguration');
    }

    protected function overwriteController(ContainerBuilder $container)
    {
        $controllerDefinitionIds = [];

        $resources = $container->getParameter('sylius.resources');
        foreach($resources as $resourceName => $values) {
            $nameParts = explode('.', $resourceName);
            $controllerDefinitionIds[] = sprintf('%s.controller.%s', $nameParts[0], $nameParts[1]);
        }

        foreach($controllerDefinitionIds as $definitionName) {
            if($container->hasDefinition($definitionName)) {
                $definition = $container->getDefinition($definitionName);
                $definition->addArgument($container->getDefinition('viewer.factory'));
                $definition->addArgument($container->getDefinition('enhavo.sorting_manager'));
            }
        }
    }

    protected function overwriteEventDispatcher(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.resource_controller.event_dispatcher');
        $definition->setClass('Enhavo\Bundle\AppBundle\Controller\EventDispatcher');
    }
}