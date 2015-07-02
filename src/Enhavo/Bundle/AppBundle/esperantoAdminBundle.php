<?php

namespace Enhavo\Bundle\AdminBundle;

use Enhavo\Bundle\AdminBundle\DependencyInjection\Compiler\RouteContentCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class enhavoAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RouteContentCompilerPass());
    }
}