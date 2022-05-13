<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Sylius\Component\Resource\Factory;
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
class EnhavoShopExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $this->registerResources('enhavo_shop', $config['driver'], $config['resources'], $container);

        $container->setParameter('enhavo_shop.document.billing', $config['document']['billing']); //Todo: Can be removed in next version
        $container->setParameter('enhavo_shop.document.billing.generator', $config['document']['billing']['generator']);
        $container->setParameter('enhavo_shop.document.billing.options', isset($config['document']['billing']['options']) ? $config['document']['billing']['options'] : []);

        $container->setParameter('enhavo_shop.document.packing_slip', $config['document']['packing_slip']); //Todo: Can be removed in next version
        $container->setParameter('enhavo_shop.document.packing_slip.generator', $config['document']['packing_slip']['generator']);
        $container->setParameter('enhavo_shop.document.packing_slip.options', isset($config['document']['packing_slip']['options']) ? $config['document']['packing_slip']['options'] : []);

        $container->setParameter('enhavo_shop.payment.methods', $config['payment']['methods']);

        $container->setParameter('enhavo_shop.product.variant_proxy.model', $config['product']['variant_proxy']['model']);
        $container->setParameter('enhavo_shop.product.variant_proxy.factory', $config['product']['variant_proxy']['factory']);

        $configFiles = array(
            'services/locale.yaml',
            'services/menu.yaml',
            'services/form.yaml',
            'services/block.yaml',
            'services/services.yaml',
            'services/promotion.yaml',
            'services/order.yaml',
            'services/view.yaml',
            'services/component.yaml',
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
            __DIR__.'/../Resources/config/app/state_machine/enhavo_order.yml',
            __DIR__.'/../Resources/config/app/state_machine/enhavo_order_checkout.yml',
            __DIR__.'/../Resources/config/app/state_machine/enhavo_order_payment.yml',
            __DIR__.'/../Resources/config/app/state_machine/enhavo_order_shipping.yml',
            __DIR__.'/../Resources/config/app/state_machine/enhavo_payment.yml',
            __DIR__.'/../Resources/config/app/state_machine/enhavo_shipment.yml',
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
