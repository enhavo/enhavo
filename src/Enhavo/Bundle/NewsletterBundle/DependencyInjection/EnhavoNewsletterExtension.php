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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $this->registerResources('enhavo_newsletter', $config['driver'], $config['resources'], $container);

        $container->setParameter('enhavo_newsletter.newsletter.mail', $config['newsletter']['mail']);

        $container->setParameter('enhavo_newsletter.storage', $config['storage']['default']);
        $container->setParameter('enhavo_newsletter.strategy', $config['strategy']['default']);
        $container->setParameter('enhavo_newsletter.forms', $config['forms']);

        if (isset($config['storage']['groups']['defaults'])) {
            $container->setParameter('enhavo_newsletter.defaultgroups', $config['storage']['groups']['defaults']);
        } else {
            $container->setParameter('enhavo_newsletter.defaultgroups', []);
        }

        if (isset($config['storage']['settings']['cleverreach']['credentials'])) {
            $container->setParameter('enhavo_newsletter.cleverreach.credentials', $config['storage']['settings']['cleverreach']['credentials']);
        } else {
            $container->setParameter('enhavo_newsletter.cleverreach.credentials', []);
        }

        if (isset($config['storage']['settings']['cleverreach']['groups']['mapping'])) {
            $container->setParameter('enhavo_newsletter.cleverreach.mapping', $config['storage']['settings']['cleverreach']['groups']['mapping']);
        } else {
            $container->setParameter('enhavo_newsletter.cleverreach.mapping', []);
        }

        if (isset($config['strategy']['settings']['notify'])) {
            $container->setParameter('enhavo_newsletter.strategy.notify.settings', $config['strategy']['settings']['notify']);
        } else {
            $container->setParameter('enhavo_newsletter.strategy.notify.settings', []);
        }

        if (isset($config['strategy']['settings']['accept'])) {
            $container->setParameter('enhavo_newsletter.strategy.accept.settings', $config['strategy']['settings']['accept']);
        } else {
            $container->setParameter('enhavo_newsletter.strategy.accept.settings', []);
        }

        if (isset($config['strategy']['settings']['double_opt_in'])) {
            $container->setParameter('enhavo_newsletter.strategy.double_opt_in.settings', $config['strategy']['settings']['double_opt_in']);
        } else {
            $container->setParameter('enhavo_newsletter.strategy.double_opt_in.settings', []);
        }

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
