<?php

namespace Enhavo\Bundle\UserBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoUserExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $this->registerResources('enhavo_user', $config['driver'], $config['resources'], $container);

        $container->setParameter('enhavo_user.user_manager', $config['user_manager']);
        $container->setParameter('enhavo_user.config_key_provider', $config['config_key_provider']);
        $container->setParameter('enhavo_user.default_firewall', $config['default_firewall']);
        $container->setParameter('enhavo_user.config', $config['config']);
        $container->setParameter('enhavo_user.mapper', $config['mapper']);

        $configFiles = array(
            'services/controller.yaml',
            'services/services.yaml',
            'services/subscriber.yaml',
            'services/command.yaml',
            'services/form.yaml',
            'services/menu.yaml',
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
