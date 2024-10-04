<?php

namespace Enhavo\Bundle\NewsletterBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class EnhavoNewsletterExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configs = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

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
            'services/endpoint.yaml',
            'services/storage.yaml',
            'services/strategy.yaml',
        );

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }

    protected function prependFiles(): array
    {
        return [
            __DIR__ . '/../Resources/config/app/config.yaml',
            __DIR__ . '/../Resources/config/resources/group.yaml',
            __DIR__ . '/../Resources/config/resources/local_subscriber.yaml',
            __DIR__ . '/../Resources/config/resources/newsletter.yaml',
            __DIR__ . '/../Resources/config/resources/pending_subscriber.yaml',
            __DIR__ . '/../Resources/config/resources/receiver.yaml',
        ];
    }
}
