<?php
/**
 * RequestConfigurationCompilerPass.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Controller\ResourcesResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SyliusCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteTargetResolver($container);
        $this->overwriteRequestConfigurationFactory($container);
        $this->overwriteController($container);
        $this->overwriteResourceResolver($container);
    }

    private function overwriteTargetResolver(ContainerBuilder $container)
    {
        $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');
        $resolveTargetEntityListener->setPublic(true);
    }

    protected function overwriteRequestConfigurationFactory(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.resource_controller.request_configuration_factory');
        $definition->replaceArgument(1, RequestConfiguration::class);
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
                $definition->addArgument($container->getDefinition('view.factory'));
                $definition->addArgument($container->getDefinition('enhavo.sorting_manager'));
                $definition->addArgument($container->getDefinition('enhavo_app.batch_manager'));
                $definition->addArgument($container->getDefinition('enhavo_app.factory.duplicate_resource_factory'));
                $definition->addArgument($container->getDefinition('enhavo_app.event_dispatcher'));
            }
        }
    }

    protected function overwriteResourceResolver(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.resource_controller.resources_resolver');
        $definition->setClass(ResourcesResolver::class);
        $definition->addArgument($container->getDefinition('enhavo_app.filter.filer_query_builder'));
    }
}