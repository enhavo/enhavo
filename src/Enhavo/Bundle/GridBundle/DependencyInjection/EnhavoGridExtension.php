<?php

namespace Enhavo\Bundle\GridBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoGridExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_grid.items', $config['items']);

        $container->setParameter('enhavo_grid.column.style_form', $config['column']['style_form']);
        $container->setParameter('enhavo_grid.column.width_form', $config['column']['width_form']);
        $container->setParameter('enhavo_grid.column.styles', $config['column']['styles']);

        if(isset($config['render']) && isset($config['render']['sets'])) {
            $container->setParameter('enhavo_grid.render.sets', $config['render']['sets']);
        } else {
            $container->setParameter('enhavo_grid.render.sets', []);
        }

        if($config['doctrine']['enable_columns']) {
            $container->setParameter('enhavo_grid.doctrine.enable_columns', true);
        }

        if($config['doctrine']['enable_items']) {
            $container->setParameter('enhavo_grid.doctrine.enable_items', true);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/command.yml');
        $loader->load('services/services.yml');
        $loader->load('services/factory.yml');
        $loader->load('services/items.yml');
        $loader->load('services/form.yml');
    }
}
