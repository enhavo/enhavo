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
    }
}