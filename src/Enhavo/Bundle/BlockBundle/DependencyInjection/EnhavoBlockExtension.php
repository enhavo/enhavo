<?php

namespace Enhavo\Bundle\BlockBundle\DependencyInjection;

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
class EnhavoBlockExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_block.blocks', $config['blocks']);

        $container->setParameter('enhavo_block.column.style_form', $config['column']['style_form']);
        $container->setParameter('enhavo_block.column.width_form', $config['column']['width_form']);
        $container->setParameter('enhavo_block.column.styles', $config['column']['styles']);
        $container->setParameter('enhavo_block.render.sets', $config['render']['sets']);

        if($config['doctrine']['enable_columns']) {
            $container->setParameter('enhavo_block.doctrine.enable_columns', true);
        }

        if($config['doctrine']['enable_blocks']) {
            $container->setParameter('enhavo_block.doctrine.enable_blocks', true);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/command.yaml');
        $loader->load('services/services.yaml');
        $loader->load('services/factory.yaml');
        $loader->load('services/blocks.yaml');
        $loader->load('services/form.yaml');
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
