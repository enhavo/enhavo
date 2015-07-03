<?php

namespace Enhavo\Bundle\AppBundle;

use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\RouteContentCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoAppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RouteContentCompilerPass());
    }
}