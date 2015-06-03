<?php

namespace esperanto\NewsletterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Sylius\Bundle\ResourceBundle\DependencyInjection\AbstractResourceExtension;
use esperanto\AdminBundle\DependencyInjection\SyliusResourceExtension;


/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class esperantoNewsletterExtension extends SyliusResourceExtension
{
    // You can choose your application name, it will use to prefix the configuration keys in the container (the default value is sylius).
    protected $applicationName = 'esperanto_newsletter';

    // You can define where yours service definitions are
    protected $configDirectory = '/../Resources/config';

    // You can define what service definitions you want to load
    protected $configFiles = array(
        'services',
        'forms',
    );

    // You can define the file formats of the files loaded
    //protected $configFormat = self::CONFIG_XML;

    public function load(array $config, ContainerBuilder $container)
    {
        $this->configure(
            $config,
            new Configuration(),
            $container,
            self::CONFIGURE_LOADER | self::CONFIGURE_DATABASE | self::CONFIGURE_PARAMETERS | self::CONFIGURE_ADMIN
        );

        $container->setParameter('esperanto_newsletter.subscriber', $config[0]['subscriber']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
