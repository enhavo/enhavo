<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EnhavoUserExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('enhavo_user.user_manager', $config['user_manager']);
        $container->setParameter('enhavo_user.config_key_provider', $config['config_key_provider']);
        $container->setParameter('enhavo_user.default_firewall', $config['default_firewall']);
        $container->setParameter('enhavo_user.config', $config['config']);
        $container->setParameter('enhavo_user.user_identifiers', $config['user_identifiers']);

        $configFiles = [
            'services/services.yaml',
            'services/security.yaml',
            'services/subscriber.yaml',
            'services/command.yaml',
            'services/form.yaml',
            'services/menu.yaml',
            'services/endpoint.yaml',
        ];
        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }

    protected function prependFiles(): array
    {
        return [
            __DIR__.'/../Resources/config/app/config.yaml',
            __DIR__.'/../Resources/config/resources/user.yaml',
            __DIR__.'/../Resources/config/resources/group.yaml',
        ];
    }
}
