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
        $this->registerResources('enhavo_theme', $config['driver'], $config['resources'], $container);

        $container->setParameter('enhavo_theme.dynamic_theme.enable', $config['dynamic_theme']['enable']);
        $container->setParameter('enhavo_theme.theme', $config['theme']);
        $container->setParameter('enhavo_theme.themes', $config['themes']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services/services.yml');
    }
}
