<?php

namespace Enhavo\Bundle\MediaBundle;

use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Enhavo\Bundle\MediaBundle\DependencyInjection\Compiler\MediaCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoMediaBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MediaCompilerPass());

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_media.extension_collector', 'enhavo.media_extension')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_media.filter_collector', 'enhavo.media_filter')
        );
    }
}
