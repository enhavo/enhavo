<?php

namespace Enhavo\Bundle\SearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoSearchExtension extends Extension implements PrependExtensionInterface
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
        $container->setParameter('enhavo_search.elastica.version', $config['elastica']['version']);
        $container->setParameter('enhavo_search.elastica.index_name', $config['elastica']['index_name']);
        $container->setParameter('enhavo_search.index.class', $config['index']['classes']);
        $container->setParameter('enhavo_search.metadata', $config['metadata']);

        if($config['doctrine']['enable_database']) {
            $container->setParameter('enhavo_search.doctrine.enable_database', true);
        }


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/services.yaml');
        $loader->load('services/metadata.yaml');
        $loader->load('services/index.yaml');
        $loader->load('services/elastic_search.yaml');
        $loader->load('services/database.yaml');
        $loader->load('services/filter.yaml');
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $configs = Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/app/config.yaml'));
        foreach($configs as $name => $config) {
            if (is_array($config)) {
                $container->prependExtensionConfig($name, $config);
            }
        }
    }
}
