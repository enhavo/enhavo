<?php

namespace Enhavo\Bundle\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoMediaExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $this->registerResources('enhavo_media', $config['driver'], $config['resources'], $container);

        $container->setParameter('enhavo_media.formats', $config['formats']);
        $container->setParameter('enhavo_media.provider', $config['provider']);
        $container->setParameter('enhavo_media.storage', $config['storage']);
        $container->setParameter('enhavo_media.filter.video_image', $config['filter']['video_image']);
        $container->setParameter('enhavo_media.filter.image_compression', $config['filter']['image_compression']);
        $container->setParameter('enhavo_media.form', $config['form']);
        $container->setParameter('enhavo_media.cache_control.max_age', $config['cache_control']['max_age']);
        $container->setParameter('enhavo_media.cache_control.class', $config['cache_control']['class']);
        $container->setParameter('enhavo_media.streaming.disabled', $config['streaming']['disabled']);
        $container->setParameter('enhavo_media.streaming.threshold', $config['streaming']['threshold']);
        $container->setParameter('enhavo_media.enable_delete_unreferenced', $config['enable_delete_unreferenced']);
        $container->setParameter('enhavo_media.upload_validation.groups', $config['upload_validation']['groups']);
        $container->setParameter('enhavo_media.upload_validation.clamav', $config['upload_validation']['clamav']);

        $configFiles = array(
            'services/command.yaml',
            'services/media.yaml',
            'services/extension.yaml',
            'services/filter.yaml',
        );

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
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
