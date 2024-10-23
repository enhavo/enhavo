<?php

namespace Enhavo\Bundle\DoctrineExtensionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoDoctrineExtensionExtension extends Extension
{
    const CONFIG_DIR = __DIR__.'/../Resources/config';

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_doctrine_extension.metadata', $config['metadata']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(self::CONFIG_DIR));
        $loader->load('services/resolver.yaml');
        $loader->load('services/command.yaml');
        $loader->load('services/metadata.yaml');
        $loader->load('services/listener.yaml');
        $loader->load('services/util.yaml');
    }
}
