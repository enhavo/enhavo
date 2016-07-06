<?php

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Extension;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension as SyliusAbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * AbstractResourceExtension.php
 *
 * @since 06/07/16
 * @author gseidel
 */
abstract class AbstractResourceExtension extends SyliusAbstractResourceExtension
{
    protected function registerResources($applicationName, $driver, array $resources, ContainerBuilder $container)
    {
        parent::registerResources($applicationName, $driver, $resources, $container);
    }
}