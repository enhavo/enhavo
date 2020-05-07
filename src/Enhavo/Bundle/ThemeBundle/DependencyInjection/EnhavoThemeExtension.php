<?php

namespace Enhavo\Bundle\ThemeBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoThemeExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $this->registerResources('enhavo_theme', $config['driver'], $config['resources'], $container);

        $container->setParameter('enhavo_theme.dynamic.enable', $config['dynamic']['enable']);
        $container->setParameter('enhavo_theme.webpack.custom_file', $config['webpack']['custom_file']);
        $container->setParameter('enhavo_theme.theme', $config['theme']);
        $container->setParameter('enhavo_theme.themes_dir', $config['themes_dir']);

        $loader->load('services/services.yaml');
    }
}
