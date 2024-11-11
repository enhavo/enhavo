<?php

namespace Enhavo\Bundle\RoutingBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

class EnhavoRoutingExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_routing.classes', $config['classes']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/cfm.yaml');
        $loader->load('services/form.yaml');
        $loader->load('services/general.yaml');
        $loader->load('services/generator.yaml');
        $loader->load('services/metadata.yaml');
        $loader->load('services/router.yaml');
    }

    protected function prependFiles(): array
    {
        $files = [
            __DIR__.'/../Resources/config/app/config.yaml',
            __DIR__.'/../Resources/config/resources/route.yaml',
        ];

        if (class_exists('\Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle')) {
            $files[] = __DIR__.'/../Resources/config/app/config-gedmo.yaml';
        }

        return $files;
    }
}
