<?php

namespace Enhavo\Bundle\CalendarBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Enhavo\Bundle\AppBundle\DependencyInjection\SyliusResourceExtension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoCalendarExtension extends SyliusResourceExtension
{
    // You can choose your application name, it will use to prefix the configuration keys in the container.
    protected $applicationName = 'enhavo_calendar';

    protected $bundleName = 'calendar';

    protected $companyName = 'enhavo';

    // You can define where yours service definitions are
    protected $configDirectory = '/../Resources/config';

    // You can define what service definitions you want to load
    protected $configFiles = array(
        'services',
        'forms',
    );

    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $this->configure(
            $config,
            new Configuration(),
            $container,
            self::CONFIGURE_LOADER | self::CONFIGURE_DATABASE | self::CONFIGURE_PARAMETERS | self::CONFIGURE_ADMIN
        );

        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration( $configuration, $config );
        $container->setParameter( 'enhavo_calendar.calendar_route', $processedConfig['calendar_route']);
        $container->setParameter( 'enhavo_calendar.dynamic_routing', $processedConfig['dynamic_routing']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
