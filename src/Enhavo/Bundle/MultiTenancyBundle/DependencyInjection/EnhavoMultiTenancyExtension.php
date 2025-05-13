<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EnhavoMultiTenancyExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('enhavo_multi_tenancy.provider', $config['provider']);
        $container->setParameter('enhavo_multi_tenancy.resolver', $config['resolver']);
        $container->setParameter('enhavo_multi_tenancy.default_tenant', $config['default_tenant']);
        $container->setParameter('enhavo_multi_tenancy.doctrine_filter', $config['doctrine_filter']);
        $container->setParameter('enhavo_multi_tenancy.tenant_switch_menu.url_prefix', $config['tenant_switch_menu']['url_prefix']);
        $container->setParameter('enhavo_multi_tenancy.tenant_switch_menu.session_key', $config['tenant_switch_menu']['session_key']);

        $configFiles = [
            'services/general.yaml',
            'services/doctrine.yaml',
            'services/menu.yaml',
            'services/resolver.yaml',
            'services/routing.yaml',
            'services/twig.yaml',
        ];

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
