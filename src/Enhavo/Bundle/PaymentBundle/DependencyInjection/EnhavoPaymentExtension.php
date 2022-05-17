<?php

namespace Enhavo\Bundle\PaymentBundle\DependencyInjection;

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
class EnhavoPaymentExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $this->registerResources('enhavo_payment', $config['driver'], $config['resources'], $container);

        $container->setParameter('enhavo_payment.payment.methods', $config['payment']['methods']);
        $container->setParameter('enhavo_payment.currencies', $config['currencies']);

        $configFiles = array(
            'services/payum.yaml',
            'services/services.yaml',
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
        $files = [
            __DIR__.'/../Resources/config/app/config.yaml',
            __DIR__.'/../Resources/config/app/state_machine/enhavo_payment.yaml',
        ];

        foreach ($files as $file) {
            $configs = Yaml::parse(file_get_contents($file));
            foreach ($configs as $name => $config) {
                if (is_array($config)) {
                    $container->prependExtensionConfig($name, $config);
                }
            }
        }
    }
}
