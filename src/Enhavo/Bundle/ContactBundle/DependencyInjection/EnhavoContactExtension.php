<?php

namespace Enhavo\Bundle\ContactBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoContactExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if(isset($config['forms']) && is_array($config['forms'])) {
            foreach($config['forms'] as $name => $form) {
                $container->setParameter(sprintf('enhavo_contact.%s.model', $name), $form['model']);
                $container->setParameter(sprintf('enhavo_contact.%s.form', $name), $form['form']);
                $container->setParameter(sprintf('enhavo_contact.%s.form_options', $name), $form['form_options']);
                $container->setParameter(sprintf('enhavo_contact.%s.recipient', $name), $form['recipient']);
                $container->setParameter(sprintf('enhavo_contact.%s.confirm', $name), $form['confirm']);
            }
            $container->setParameter('enhavo_contact.forms', $config['forms']);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
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
