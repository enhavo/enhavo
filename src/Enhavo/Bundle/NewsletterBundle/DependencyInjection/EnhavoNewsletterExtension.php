<?php

namespace Enhavo\Bundle\NewsletterBundle\DependencyInjection;

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
class EnhavoNewsletterExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $this->registerResources('enhavo_newsletter', $config['driver'], $config['resources'], $container);

        $container->setParameter('enhavo_newsletter.newsletter.mail.from', $config['newsletter']['mail']['from']);
        $container->setParameter('enhavo_newsletter.newsletter.templates', $config['newsletter']['templates']);

        $container->setParameter('enhavo_newsletter.subscribtion', $config['subscribtion']);

        if (isset($config['forms'])) {
            $container->setParameter('enhavo_newsletter.forms', $config['forms']);
        } else {
            $container->setParameter('enhavo_newsletter.forms', []);
        }

        $configFiles = array(
            'services/services.yaml',
            'services/newsletter.yaml',
            'services/subscriber.yaml',
            'services/storage.yaml',
            'services/strategy.yaml',
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
        $configs = Yaml::parse(file_get_contents(__DIR__ . '/../Resources/config/app/config.yaml'));
        foreach ($configs as $name => $config) {
            if (is_array($config)) {
                $container->prependExtensionConfig($name, $config);
            }
        }
    }
}
