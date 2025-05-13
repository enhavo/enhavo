<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\DependencyInjection;

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
class EnhavoFormExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_form.wysiwyg.editor_entrypoint', $config['wysiwyg']['editor_entrypoint']);
        $container->setParameter('enhavo_form.wysiwyg.editor_entrypoint_build', $config['wysiwyg']['editor_entrypoint_build']);
        $container->setParameter('enhavo_form.date_type.config', $config['date_type']['config']);
        $container->setParameter('enhavo_form.date_time_type.config', $config['date_time_type']['config']);
        $container->setParameter('enhavo_form.html_sanitizer.config', $config['html_sanitizer']['config']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/form.yaml');
        $loader->load('services/serializer.yaml');
        $loader->load('services/services.yaml');
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
