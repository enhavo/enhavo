<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContactBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoContactExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (isset($config['forms']) && is_array($config['forms'])) {
            foreach ($config['forms'] as $name => $form) {
                $container->setParameter(sprintf('enhavo_contact.%s.model', $name), $form['model']);
                $container->setParameter(sprintf('enhavo_contact.%s.form', $name), $form['form']);
                $container->setParameter(sprintf('enhavo_contact.%s.form_options', $name), $form['form_options']);
                $container->setParameter(sprintf('enhavo_contact.%s.label', $name), $form['label']);
                $container->setParameter(sprintf('enhavo_contact.%s.translation_domain', $name), $form['translation_domain']);
                $container->setParameter(sprintf('enhavo_contact.%s.template', $name), $form['template']);
                $container->setParameter(sprintf('enhavo_contact.%s.recipient', $name), $form['recipient']);
                $container->setParameter(sprintf('enhavo_contact.%s.confirm', $name), $form['confirm']);
            }
            $container->setParameter('enhavo_contact.forms', $config['forms']);
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
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
