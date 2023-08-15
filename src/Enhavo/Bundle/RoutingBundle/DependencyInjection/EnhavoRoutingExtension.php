<?php

namespace Enhavo\Bundle\RoutingBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class EnhavoRoutingExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $this->registerResources('enhavo_routing', $config['driver'], $config['resources'], $container);

        $container->setParameter('enhavo_routing.classes', $config['classes']);
        $container->setParameter('enhavo_routing.condition_resolver', $config['condition_resolver']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/cfm.yaml');
        $loader->load('services/form.yaml');
        $loader->load('services/general.yaml');
        $loader->load('services/generator.yaml');
        $loader->load('services/metadata.yaml');
        $loader->load('services/router.yaml');
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $configs = Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/app/config.yaml'));

        if (class_exists('\Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle')) {
            $configs = array_merge($configs, Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/app/config-gedmo.yaml')));
        }

        foreach($configs as $name => $config) {
            if (is_array($config)) {
                $container->prependExtensionConfig($name, $config);
            }
        }
    }
}
