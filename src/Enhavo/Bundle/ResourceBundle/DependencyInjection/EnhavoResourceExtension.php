<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class EnhavoResourceExtension extends Extension
{
    use ResourceExtensionTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $gridConfigurationMerger = new GridConfigurationMerger();
        $configs = $gridConfigurationMerger->performMerge($configs);

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_resources.grids', $config['grids']);
        $container->setParameter('enhavo_resources.inputs', $config['inputs']);

        $this->registerResources($config['resources'], $container);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services/action.yaml');
        $loader->load('services/batch.yaml');
        $loader->load('services/column.yaml');
        $loader->load('services/endpoint.yaml');
        $loader->load('services/filter.yaml');
        $loader->load('services/grid.yaml');
        $loader->load('services/services.yaml');
        $loader->load('services/collection.yaml');
        $loader->load('services/input.yaml');
        $loader->load('services/tab.yaml');
    }
}
