<?php

namespace esperanto\AdminBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\AbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

abstract class SyliusResourceExtension extends AbstractResourceExtension
{
    const CONFIGURE_ADMIN = 16;

    protected $bundleName;

    protected $companyName;

    public function loadAdmin(array $config, ContainerBuilder $container)
    {
        $classes = isset($config['classes']) ? $config['classes'] : array();

        foreach($classes as $entity => $class) {
            if(isset($class['admin'])) {
                $loader = new AdminLoader($container);
                $loader->setClass($class['admin']);
                $loader->setEntityName($entity);
                $loader->setBundleName($this->bundleName);
                $loader->setCompanyName($this->companyName);
                $loader->setApplicationName($this->applicationName);
                $loader->load();
            }
        }
    }

    public function configure(
        array $config,
        ConfigurationInterface $configuration,
        ContainerBuilder $container,
        $configure = self::CONFIGURE_LOADER
    ) {
        parent::configure($config, $configuration, $container, $configure);
        $processor = new Processor();

        $config = $processor->processConfiguration($configuration, $config);
        $config = $this->process($config, $container);

        #if ($configure & self::CONFIGURE_ADMIN) {
        #    $this->loadAdmin($config, $container);
        #}
    }
}