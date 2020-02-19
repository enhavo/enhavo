<?php

namespace Enhavo\Bundle\RoutingBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class EnhavoRoutingExtension extends AbstractResourceExtension
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
        $loader->load('services/cfm.yml');
        $loader->load('services/form.yml');
        $loader->load('services/general.yml');
        $loader->load('services/generator.yml');
        $loader->load('services/metadata.yml');
        $loader->load('services/router.yml');
    }
}
