<?php
/**
 * MediaBundle.php
 *
 * @since 03/09/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MediaCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->createProviderAlias($container);
        $this->createStorageAlias($container);
        $this->createCacheAlias($container);
    }

    public function createProviderAlias(ContainerBuilder $container)
    {
        $providerServiceName = $container->getParameter('enhavo_media.provider');
        $container->setAlias('enhavo_media.provider', $providerServiceName);
    }

    public function createStorageAlias(ContainerBuilder $container)
    {
        $providerServiceName = $container->getParameter('enhavo_media.storage');
        $container->setAlias('enhavo_media.storage', $providerServiceName);
    }

    public function createCacheAlias(ContainerBuilder $container)
    {
        $providerServiceName = $container->getParameter('enhavo_media.cache_control.class');
        $container->setAlias('enhavo_media.cache', $providerServiceName);
    }
}
