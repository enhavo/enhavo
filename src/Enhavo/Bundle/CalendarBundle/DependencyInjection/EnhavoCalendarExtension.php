<?php

namespace Enhavo\Bundle\CalendarBundle\DependencyInjection;

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
class EnhavoCalendarExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $this->registerResources('enhavo_calendar', $config['driver'], $config['resources'], $container);

        $container->setParameter('enhavo_calendar.routing.appointment.strategy', $config['resources']['appointment']['routing']['strategy']);
        $container->setParameter('enhavo_calendar.routing.appointment.route', $config['resources']['appointment']['routing']['route']);

        $configFiles = array(
            'services.yml',
        );
        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }
}
