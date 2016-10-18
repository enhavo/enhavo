<?php

namespace Enhavo\Bundle\NewsletterBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Sylius\Component\Resource\Factory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoNewsletterExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $this->registerResources('enhavo_newsletter', $config['driver'], $config['resources'], $container);

        $container->setParameter('enhavo_newsletter.subscriber.strategy', $config['subscriber']['strategy']);
        $container->setParameter('enhavo_newsletter.subscriber.strategy_options', $config['subscriber']['strategy_options']);
        $container->setParameter('enhavo_newsletter.subscriber.form', $config['subscriber']['form']);

        $container->setParameter('enhavo_newsletter.newsletter.mail', $config['newsletter']['mail']);

        $container->setParameter('enhavo_newsletter.storage', $config['storage']);

        $container->setParameter('enhavo_newsletter.cleverreach.credentials', $config['resources']['newsletter']['options']['credentials']);
        $container->setParameter('enhavo_newsletter.cleverreach.group', $config['resources']['newsletter']['options']['group']);

        $configFiles = array(
            'services/services.yml',
            'services/newsletter.yml',
            'services/subscriber.yml',
        );

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }
}
