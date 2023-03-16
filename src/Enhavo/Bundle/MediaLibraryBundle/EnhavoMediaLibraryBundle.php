<?php

namespace Enhavo\Bundle\MediaLibraryBundle;

use Enhavo\Bundle\MediaLibraryBundle\DependencyInjection\Compiler\ReplaceFileFactoryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoMediaLibraryBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new ReplaceFileFactoryCompilerPass()
        );
    }
}
