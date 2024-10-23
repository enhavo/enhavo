<?php

namespace Enhavo\Bundle\ContentBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EnhavoContentExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $collectors = [];
        if(isset($config['sitemap']) && isset($config['sitemap']['collectors'])) {
            $collectors = $config['sitemap']['collectors'];
        }
        $container->setParameter('enhavo_content.sitemap.collectors', $collectors);

        $container->setParameter('enhavo_content.video.vimeo.api_key', $config['video']['vimeo']['api_key']);
        $container->setParameter('enhavo_content.video.youtube.api_key', $config['video']['youtube']['api_key']);

        $configFiles = array(
            'services.yaml',
        );

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }

    protected function prependFiles(): array
    {
        return [
            __DIR__.'/../Resources/config/app/config.yaml',
        ];
    }
}
