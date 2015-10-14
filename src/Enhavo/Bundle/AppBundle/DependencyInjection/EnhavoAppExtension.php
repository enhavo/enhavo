<?php

namespace Enhavo\Bundle\AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoAppExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_app.permission_check', $config[ 'permission_check' ]);
        $container->setParameter('enhavo_app.stylesheets', $config[ 'stylesheets' ]);
        $container->setParameter('enhavo_app.javascripts', $config[ 'javascripts' ]);
        $container->setParameter('enhavo_app.menu', $config[ 'menu' ]);
        $container->setParameter('enhavo_app.viewer', $config[ 'viewer' ]);
        $container->setParameter('enhavo_app.dynamic_routing', $config[ 'dynamic_routing' ]);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('twig.yml');
        $loader->load('viewer.yml');
        $loader->load('block.yml');

        if($config[ 'dynamic_routing' ]) {
            $loader->load('dynamic_routing.yml');
        }
    }
}
