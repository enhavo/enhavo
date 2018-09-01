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
        $container->setParameter('enhavo_search.search.indexing', $config['search']['indexing']);
        $container->setParameter('enhavo_search.search.template', $config['search']['template']);
        $container->setParameter('enhavo_search.search.engine', $config['search']['engine']);
        $container->setParameter('enhavo_search.elastica.host', $config['elastica']['host']);
        $container->setParameter('enhavo_search.elastica.port', $config['elastica']['port']);
        $container->setParameter('enhavo_search.index.class', $config['index']['classes']);

        if($config['doctrine']['enable_database']) {
            $container->setParameter('enhavo_search.doctrine.enable_database', true);
        }


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/services.yml');
        $loader->load('services/general.yml');
        $loader->load('services/metadata.yml');
        $loader->load('services/extractor.yml');
        $loader->load('services/indexer.yml');
        $loader->load('services/elastic_search.yml');
        $loader->load('services/database.yml');
        $loader->load('services/filter.yml');
    }
}
