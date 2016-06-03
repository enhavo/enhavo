<?php

namespace Enhavo\Bundle\SearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoSearchExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('enhavo_search.search.type', 'search');
        $container->setParameter('enhavo_search.search.template', $config['search']['template']);
        $container->setParameter('enhavo_search.search.strategy', $config['search']['strategy']);
        $container->setParameter('enhavo_search.search.search_engine', $config['search']['search_engine']);
        $container->setParameter('enhavo_search.search.index_engine', $config['search']['index_engine']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
