<?php

namespace Enhavo\Bundle\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoMediaExtension extends AbstractResourceExtension
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

        $configFiles = array(
            'services/media.yml',
            'services/extension.yml',
            'services/filter.yml',
        );

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }
}
