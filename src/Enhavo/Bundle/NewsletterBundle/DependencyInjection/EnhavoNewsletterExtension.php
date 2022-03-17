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
    public function load(array $configs, ContainerBuilder $container)
    {
        $configs = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $this->registerResources('enhavo_newsletter', $configs['driver'], $configs['resources'], $container);

        $container->setParameter('enhavo_newsletter.newsletter.mail.from', $configs['newsletter']['mail']['from']);
        $container->setParameter('enhavo_newsletter.newsletter.test_receiver', $configs['newsletter']['test_receiver']);
        $container->setParameter('enhavo_newsletter.newsletter.templates', $configs['newsletter']['templates']);
        $container->setParameter('enhavo_newsletter.newsletter.provider', $configs['newsletter']['provider']);

        $container->setParameter('enhavo_newsletter.subscription', $configs['subscription']);

        if (isset($configs['forms'])) {
            $container->setParameter('enhavo_newsletter.forms', $configs['forms']);
        } else {
            $container->setParameter('enhavo_newsletter.forms', []);
        }

        $configFiles = array(
            'services/services.yaml',
            'services/newsletter.yaml',
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
