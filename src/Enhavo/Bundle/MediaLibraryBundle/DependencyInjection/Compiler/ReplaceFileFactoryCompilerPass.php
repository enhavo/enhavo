<?php

namespace Enhavo\Bundle\MediaLibraryBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReplaceFileFactoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $alias = $container->setAlias('enhavo_media.factory.file', 'enhavo_media_library.file.factory');
        $alias->setPublic(true);
    }
}
