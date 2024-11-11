<?php

namespace Enhavo\Bundle\SettingBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class EnhavoSettingExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('enhavo_setting.settings', $config['settings'] ?? []);
        $container->setParameter('enhavo_setting.groups', $config['groups'] ?? []);

        $configFiles = array(
            'services/services.yaml',
            'services/setting.yaml',
        );
        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }

    public function prependFiles(): array
    {
        return [
            __DIR__.'/../Resources/config/app/config.yaml',
            __DIR__.'/../Resources/config/resources/setting.yaml',
        ];
    }
}
