<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoBlockExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_block.blocks', $config['blocks']);

        $container->setParameter('enhavo_block.column.style_form', $config['column']['style_form']);
        $container->setParameter('enhavo_block.column.width_form', $config['column']['width_form']);
        $container->setParameter('enhavo_block.column.styles', $config['column']['styles']);
        $container->setParameter('enhavo_block.render.sets', $config['render']['sets']);

        if ($config['doctrine']['enable_columns']) {
            $container->setParameter('enhavo_block.doctrine.enable_columns', true);
        }

        if ($config['doctrine']['enable_blocks']) {
            $container->setParameter('enhavo_block.doctrine.enable_blocks', true);
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/command.yaml');
        $loader->load('services/services.yaml');
        $loader->load('services/block.yaml');
        $loader->load('services/form.yaml');
    }

    protected function prependFiles(): array
    {
        return [
            __DIR__.'/../Resources/config/app/config.yaml',
            __DIR__.'/../Resources/config/resources/node.yaml',
        ];
    }
}
