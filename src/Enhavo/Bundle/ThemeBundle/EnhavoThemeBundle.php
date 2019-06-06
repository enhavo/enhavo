<?php

namespace Enhavo\Bundle\ThemeBundle;

use Enhavo\Bundle\ThemeBundle\DependencyInjection\Compiler\SymfonyCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoThemeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SymfonyCompilerPass());
    }
}
