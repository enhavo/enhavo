<?php
/**
 * RequestConfigurationCompilerPass.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\AppBundle\Batch\BatchManager;
use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Controller\ResourcesResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SyliusCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->setSyliusResourceParameter($container);
        $this->overwriteRequestConfigurationFactory($container);
        $this->overwriteController($container);
        $this->overwriteResourceResolver($container);
        $this->setSyliusFormClassParameters($container);
    }

    private function setSyliusFormClassParameters(ContainerBuilder $container)
    {
        if ($container->hasParameter('sylius.resources')) {
            $parameters = $container->getParameter('sylius.resources');
            foreach ($parameters as $key => $value) {
                if (isset($value['classes']['form'])) {
                    $parts = explode('.', $key);
                    $formClass = $value['classes']['form'];
                    $paramName = array_shift($parts) . '.form.' . implode('.', $parts) . '.class';
                    $container->setParameter($paramName, $formClass);
                }
            }
        }
    }

    private function setSyliusResourceParameter(ContainerBuilder $container)
    {
        if (!$container->hasParameter('sylius.resources')) {
            $container->setParameter('sylius.resources', []);
        }
    }

    private function overwriteRequestConfigurationFactory(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.resource_controller.request_configuration_factory');
        $definition->replaceArgument(1, RequestConfiguration::class);
    }

    private function overwriteController(ContainerBuilder $container)
    {
        $controllerDefinitionIds = [];

        $resources = $container->hasParameter('sylius.resources') ? $container->getParameter('sylius.resources') : [];

        foreach ($resources as $resourceName => $values) {
            $nameParts = explode('.', $resourceName);
            $controllerDefinitionIds[] = sprintf('%s.controller.%s', $nameParts[0], $nameParts[1]);
        }

        foreach ($controllerDefinitionIds as $definitionName) {
            if($container->hasDefinition($definitionName)) {
                $definition = $container->getDefinition($definitionName);
                $definition->addMethodCall('setViewFactory', [$container->getDefinition('Enhavo\Component\Type\FactoryInterface[View]')]);
            }
        }
    }

    private function overwriteResourceResolver(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.resource_controller.resources_resolver');
        $definition->setClass(ResourcesResolver::class);
        $definition->addArgument($container->getDefinition('enhavo_app.filter.filer_query_builder'));
    }
}
