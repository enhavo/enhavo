<?php

namespace Enhavo\Bundle\CalendarBundle\DependencyInjection;

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
class EnhavoCalendarExtension extends AbstractResourceExtension implements PrependExtensionInterface
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
        $container->setParameter('enhavo_calendar.imports', $config['importer']);
        $container->setParameter('enhavo_calendar.export_name', $config['exporter']['calendarName']);

        $configFiles = array(
            'services.yaml',
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
        $configs = Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/app/config.yaml'));
        foreach($configs as $name => $config) {
            if (is_array($config)) {
                $container->prependExtensionConfig($name, $config);
            }
        }
    }
}
