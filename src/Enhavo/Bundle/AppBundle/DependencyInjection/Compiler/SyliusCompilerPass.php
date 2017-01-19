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
        $this->overwriteResourceResolver($container);
        $this->overwriteViewHandler($container);
        $this->overwriteNewResourceFactory($container);
    }

    protected function overwriteRequestConfigurationFactory(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.resource_controller.request_configuration_factory');
        $definition->setClass('Enhavo\Bundle\AppBundle\Controller\RequestConfigurationFactory');
        $definition->replaceArgument(1, 'Enhavo\Bundle\AppBundle\Controller\RequestConfiguration');
        $definition->addArgument('Enhavo\Bundle\AppBundle\Controller\SimpleRequestConfiguration');
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
                $definition->addArgument($container->getDefinition('enhavo_app.batch_manager'));
            }
        }
    }

    protected function overwriteEventDispatcher(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.resource_controller.event_dispatcher');
        $definition->setClass('Enhavo\Bundle\AppBundle\Controller\EventDispatcher');
    }

    protected function overwriteResourceResolver(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.resource_controller.resources_resolver');
        $definition->setClass('Enhavo\Bundle\AppBundle\Controller\ResourcesResolver');
        $definition->addArgument($container->getDefinition('enhavo_app.filter.filer_query_builder'));
    }

    protected function overwriteViewHandler(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.resource_controller.view_handler');
        $definition->setClass('Enhavo\Bundle\AppBundle\Controller\ViewHandler');
    }

    protected function overwriteNewResourceFactory(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.resource_controller.new_resource_factory');
        $definition->setClass('Enhavo\Bundle\AppBundle\Controller\DuplicateResourceFactory');
    }
}