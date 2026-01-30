<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FrameworkBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class EnhavoFrameworkExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_framework.mailer.mails', $config['mailer']['mails']);
        $container->setParameter('enhavo_framework.mailer.defaults', $config['mailer']['defaults']);
        $container->setParameter('enhavo_framework.mailer.model', $config['mailer']['model']);
        $container->setParameter('enhavo_framework.template_paths', $config['template_paths']);
        $container->setParameter('enhavo_framework.locale', $config['locale']);
        $container->setParameter('enhavo_framework.locale_resolver', $config['locale_resolver']);
        $container->setParameter('enhavo_framework.vue.route_providers', $config['vue']['route_providers'] ?? []);
        $container->setParameter('enhavo_framework.endpoint.template_url_prefix', $config['endpoint']['template_url_prefix'] ?? null);
        $container->setParameter('enhavo_framework.areas', $config['area'] ?? []);
        $container->setParameter('enhavo_framework.vite.builds', $config['vite']['builds'] ?? []);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services/area.yaml');
        $loader->load('services/services.yaml');
        $loader->load('services/endpoint.yaml');
        $loader->load('services/init.yaml');
        $loader->load('services/locale.yaml');
        $loader->load('services/command.yaml');
        $loader->load('services/maker.yaml');
        $loader->load('services/twig.yaml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $configs = Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/app/config.yaml'));
        foreach ($configs as $name => $config) {
            if (is_array($config)) {
                $container->prependExtensionConfig($name, $config);
            }
        }
    }
}
