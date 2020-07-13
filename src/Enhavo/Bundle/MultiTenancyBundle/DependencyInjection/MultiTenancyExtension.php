<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 21.11.17
 * Time: 10:33
 */

namespace Bundle\MultiTenancyBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class MultiTenancyExtension extends AbstractResourceExtension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('multiTenancy.configuration', $config['configuration']);

        $configFiles = array(
            'services.yml'
        );

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }
}
