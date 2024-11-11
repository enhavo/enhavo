<?php

namespace Enhavo\Bundle\MediaBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EnhavoMediaExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('enhavo_media.formats', $config['formats']);
        $container->setParameter('enhavo_media.storage', $config['storage']);
        $container->setParameter('enhavo_media.checksum_generator', $config['checksum_generator']);
        $container->setParameter('enhavo_media.filter.video_image', $config['filter']['video_image']);
        $container->setParameter('enhavo_media.filter.image_compression', $config['filter']['image_compression']);
        $container->setParameter('enhavo_media.form', $config['form']);
        $container->setParameter('enhavo_media.cache_control.max_age', $config['cache_control']['max_age']);
        $container->setParameter('enhavo_media.cache_control.class', $config['cache_control']['class']);
        $container->setParameter('enhavo_media.streaming.disabled', $config['streaming']['disabled']);
        $container->setParameter('enhavo_media.streaming.threshold', $config['streaming']['threshold']);
        $container->setParameter('enhavo_media.clam_av', $config['clam_av']);
        $container->setParameter('enhavo_media.garbage_collection.enabled', $config['garbage_collection']['enabled']);
        $container->setParameter('enhavo_media.garbage_collection.enable_listener', $config['garbage_collection']['enable_listener']);
        $container->setParameter('enhavo_media.garbage_collection.enable_delete_unreferenced', $config['garbage_collection']['enable_delete_unreferenced']);
        $container->setParameter('enhavo_media.garbage_collection.enable_delete_marked_garbage', $config['garbage_collection']['enable_delete_marked_garbage']);
        $container->setParameter('enhavo_media.garbage_collection.garbage_collector', $config['garbage_collection']['garbage_collector']);
        $container->setParameter('enhavo_media.garbage_collection.max_items_per_run', $config['garbage_collection']['max_items_per_run']);
        $container->setParameter('enhavo_media.file_not_found.handler', $config['file_not_found']['handler']);
        $container->setParameter('enhavo_media.file_not_found.parameters', $config['file_not_found']['parameters']);

        $configFiles = array(
            'services/command.yaml',
            'services/endpoint.yaml',
            'services/media.yaml',
            'services/filter.yaml',
            'services/garbage_collection.yaml',
            'services/file_not_found.yaml',
        );

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }

    protected function prependFiles(): array
    {
        return [
            __DIR__.'/../Resources/config/app/config.yaml',
            __DIR__.'/../Resources/config/resources/file.yaml',
            __DIR__.'/../Resources/config/resources/format.yaml',
        ];
    }
}
